<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ActivityLog;
use App\Models\CourseModule;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Models\CourseCategory;
use DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::with('category')->withCount('modules');
        $categories = CourseCategory::orderBy('name')->get();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('short_title', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%")
                    ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $query->latest();
        $perPage = $request->get('per_page', 25);
        $courses = $query->paginate($perPage);

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'       => 'required|in:publish,draft,archive,delete',
            'course_ids'   => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $action    = $request->action;
        $courseIds = $request->course_ids;

        switch ($action) {
            case 'publish':
                Course::whereIn('id', $courseIds)->update(['status' => 'published']);
                $message = count($courseIds) . ' course(s) published successfully';
                break;
            case 'draft':
                Course::whereIn('id', $courseIds)->update(['status' => 'draft']);
                $message = count($courseIds) . ' course(s) moved to draft';
                break;
            case 'archive':
                Course::whereIn('id', $courseIds)->update(['status' => 'archived']);
                $message = count($courseIds) . ' course(s) archived successfully';
                break;
            case 'delete':
                Course::whereIn('id', $courseIds)->delete();
                $message = count($courseIds) . ' course(s) deleted successfully';
                break;
            default:
                $message = 'Unknown action';
        }

        return redirect()->route('admin.courses.index')->with('success', $message);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CourseCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.courses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Course Store Request:', ['input' => $request->except(['_token', '_method'])]);

        // Normalise checkboxes before validation
        $request->merge([
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'is_popular'  => $request->has('is_popular')  ? 1 : 0,
        ]);

        $validated = $this->validateRequest($request);
        // Remove bulk_modules from the fields we save to the courses table
        $courseData = Arr::except($validated, ['bulk_modules', 'deleted_at']);

        try {
            if ($request->hasFile('image')) {
                $courseData['image'] = $request->file('image')->store('courses/images', 'public');
            }

            if ($request->hasFile('banner_image')) {
                $courseData['banner_image'] = $request->file('banner_image')->store('courses/banner', 'public');
            }

            if ($request->video_type === 'upload' && $request->hasFile('video')) {
                $courseData['video'] = $request->file('video')->store('courses/videos', 'public');
            }

            $course = Course::create($courseData);

            if ($request->filled('bulk_modules')) {
                $this->processBulkModules($course, $request->bulk_modules);
            }

            if ($request->hasFile('materials')) {
                $this->uploadMaterials($course, $request->file('materials'));
            }

            return redirect()->route('admin.courses.show', $course->slug)
                ->with('success', 'Course created successfully!');

        } catch (\Exception $e) {
            \Log::error('Course store failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error creating course: ' . $e->getMessage());
        }
    }

    /**
     * Centralised validation rules for store and update.
     *
     * FIX: removed the `lte:price` rule on discount_price — that rule causes a
     * validation failure when discount_price == price (both 100). We handle
     * the business-logic check manually after validation instead.
     */
    private function validateRequest(Request $request, ?Course $course = null): array
    {
        $rules = [
            'title'                => 'required|string|max:255',
            'short_title'          => 'required|string|max:100',
            'short_description'    => 'required|string',
            'category_id'          => 'nullable|exists:course_categories,id',
            'igrcfp_category'      => 'nullable|in:IGRCFP Certificates,IGRCFP Diploma,IGRCFP Advanced Diploma,Certified GRC & Financial Crime Specialist,Postgraduate Diploma, IGRCFP Fellowship',
            'full_description'     => 'required|string',
            'image'                => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_type'           => 'nullable|in:none,upload,youtube,vimeo',
            'video'                => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:20480',
            'video_url'            => 'nullable|url|max:500',
            'level'                => 'required|in:beginner,intermediate,advanced,expert',
            'format'               => 'required|in:online,live,hybrid',
            'duration'             => 'required|string|max:100',
            'total_modules'        => 'required|integer|min:1',
            'total_hours'          => 'required|integer|min:1',
            'certification_name'   => 'required|string',
            'certifying_body'      => 'required|string',
            // FIX: no `lte:price` here — we check manually below
            'price'                => 'nullable|numeric|min:0',
            'discount_price'       => 'nullable|numeric|min:0',
            'target_audience'      => 'nullable|string',
            'learning_outcomes'    => 'nullable|string',
            'prerequisites'        => 'nullable|string',
            'career_pathways'      => 'nullable|string',
            'assessment_structure' => 'nullable|string',
            'code_of_conduct'      => 'nullable|string',
            'programme_overview'   => 'nullable|string',
            'programme_architecture' => 'nullable|string',
            'meta_description'     => 'nullable|string',
            'meta_keywords'        => 'nullable|string',
            'status'               => 'required|in:draft,published,archived',
            // FIX: accept boolean/integer from the merged checkbox values
            'is_featured'          => 'nullable|boolean',
            'is_popular'           => 'nullable|boolean',
            'sort_order'           => 'nullable|integer',
            'bulk_modules'         => 'nullable|string',
        ];

        if ($request->video_type === 'upload') {
            $rules['video'] = 'required|file|mimes:mp4,mov,avi,wmv,mkv|max:20480';
        } elseif (in_array($request->video_type, ['youtube', 'vimeo'])) {
            $rules['video_url'] = 'required|url';
        }

        $validated = $request->validate($rules);

        // Manual discount check (avoids lte:price breaking on equal values)
        $price    = (float) ($validated['price']          ?? 0);
        $discount = (float) ($validated['discount_price'] ?? 0);
        if ($discount > 0 && $discount > $price) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'discount_price' => 'Discount price cannot be greater than the regular price.',
            ]);
        }

        return $validated;
    }

    public function show(Course $course)
    {
        $course->load(['modules.sections', 'materials', 'category']);
        $categories = CourseCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.courses.show', compact('course', 'categories'));
    }

    public function edit(Course $course)
    {
        $course->load(['modules', 'materials']);
        $categories = CourseCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Key fixes applied:
     *  1. Checkbox normalisation before validation (unchecked = absent from request).
     *  2. bulk_modules excluded from $courseData going into Course::update().
     *  3. HTML table parser added so CKEditor-formatted module tables work.
     *  4. Full exception message surfaced instead of generic error.
     *  5. Redirect uses $course->fresh()->slug in case the slug changed.
     */
    public function update(Request $request, $slug)
    {
        $course = is_numeric($slug)
            ? Course::findOrFail($slug)
            : Course::where('slug', $slug)->firstOrFail();

        // Strip soft-delete field — never let it slip through
        $request->request->remove('deleted_at');

        // FIX: unchecked checkboxes are simply absent from the POST data.
        // Inject explicit 0 so the model gets updated correctly.
        $request->merge([
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'is_popular'  => $request->has('is_popular')  ? 1 : 0,
        ]);

        $validated = $this->validateRequest($request, $course);

        // FIX: never write these into the courses table
        $courseData = Arr::except($validated, ['bulk_modules', 'deleted_at']);

        // Force proper booleans (validation can return "0"/"1" strings)
        $courseData['is_featured'] = (bool) ($courseData['is_featured'] ?? false);
        $courseData['is_popular']  = (bool) ($courseData['is_popular']  ?? false);

        try {
            // Handle image replacement
            if ($request->hasFile('image')) {
                if ($course->image && Storage::disk('public')->exists($course->image)) {
                    Storage::disk('public')->delete($course->image);
                }
                $courseData['image'] = $request->file('image')->store('courses/images', 'public');
            }

            if ($request->hasFile('banner_image')) {
                if ($course->banner_image && Storage::disk('public')->exists($course->banner_image)) {
                    Storage::disk('public')->delete($course->banner_image);
                }
                $courseData['banner_image'] = $request->file('banner_image')->store('courses/banner', 'public');
            }

            if ($request->video_type === 'upload' && $request->hasFile('video')) {
                if ($course->video && Storage::disk('public')->exists($course->video)) {
                    Storage::disk('public')->delete($course->video);
                }
                $courseData['video'] = $request->file('video')->store('courses/videos', 'public');
            }

            $course->update($courseData);

            // Safety net: restore if somehow soft-deleted during update
            if ($course->trashed()) {
                \Log::warning('Course became soft-deleted during update — restoring.', [
                    'course_id' => $course->id,
                ]);
                $course->restore();
                $course->refresh();
            }

            // Handle bulk modules (replace = true on update)
            if ($request->filled('bulk_modules')) {
                $this->processBulkModules($course, $request->bulk_modules, true);
            }

            return redirect()
                ->route('admin.courses.show', $course->fresh()->slug)
                ->with('success', 'Course updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so Laravel handles them normally
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Course update failed: ' . $e->getMessage(), [
                'course_id' => $course->id,
                'trace'     => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                // FIX: show the real error message so you can debug
                ->with('error', 'Error updating course: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            foreach ([$course->image, $course->banner_image, $course->video] as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            foreach ($course->materials as $material) {
                if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }
            }

            $course->delete();

            return redirect()->route('admin.courses.index')
                ->with('success', 'Course deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting course: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    //  Bulk module processing
    // -------------------------------------------------------------------------

    /**
     * Entry point: decide whether the content is an HTML table (from CKEditor)
     * or plain text, then create/replace modules accordingly.
     */
    private function processBulkModules(Course $course, string $bulkContent, bool $replace = false): void
    {
        $modules = $this->parseBulkModules($bulkContent);

        if (empty($modules)) {
            \Log::info('processBulkModules: no modules parsed from bulk content', [
                'course_id'    => $course->id,
                'content_head' => substr($bulkContent, 0, 200),
            ]);
            return;
        }

        if ($replace) {
            $course->modules()->delete();
        }

        foreach ($modules as $moduleData) {
            $course->modules()->create($moduleData);
        }

        \Log::info('processBulkModules: created ' . count($modules) . ' modules', [
            'course_id' => $course->id,
        ]);
    }

    /**
     * Detect format and delegate to the appropriate parser.
     */
    private function parseBulkModules(string $content): array
    {
        $trimmed = trim($content);

        // CKEditor wraps tables in <figure class="table"> or plain <table>
        if (stripos($trimmed, '<table') !== false) {
            return $this->parseBulkModulesFromHtmlTable($trimmed);
        }

        return $this->parseBulkModulesFromPlainText($trimmed);
    }

    /**
     * Parse an HTML table produced by CKEditor.
     *
     * Expected columns (in order): Module number | Title | Theme (optional)
     * Header rows (containing <th>) are automatically skipped.
     */
    private function parseBulkModulesFromHtmlTable(string $html): array
    {
        $modules = [];

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8"?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $rows = $dom->getElementsByTagName('tr');

        foreach ($rows as $row) {
            // Skip header rows
            if ($row->getElementsByTagName('th')->length > 0) {
                continue;
            }

            $cells = $row->getElementsByTagName('td');

            if ($cells->length < 2) {
                continue;
            }

            $moduleCell = trim($cells->item(0)->textContent); // e.g. "Module 1"
            $titleCell  = trim($cells->item(1)->textContent);
            $themeCell  = $cells->length >= 3 ? trim($cells->item(2)->textContent) : '';

            if (empty($titleCell)) {
                continue;
            }

            // Extract the number from "Module 1", "Module 1:", etc.
            preg_match('/(\d+)/', $moduleCell, $matches);
            $moduleNumber = isset($matches[1]) ? (int) $matches[1] : 0;

            $modules[] = [
                'module_number'       => $moduleNumber,
                'title'               => $titleCell,
                'short_description'   => $themeCell ? "Theme: {$themeCell}" : '',
                'learning_objectives' => '',
                'topics_covered'      => '',
                'full_content'        => $themeCell ? "Theme: {$themeCell}" : '',
                'estimated_hours'     => 2,
            ];
        }

        return $modules;
    }

    /**
     * Parse plain-text bulk module content.
     * Format:
     *   Module 1: Title
     *   Description text…
     *
     *   Objectives:
     *   - …
     *
     *   Module 2: Title
     *   …
     */
    private function parseBulkModulesFromPlainText(string $content): array
    {
        $modules       = [];
        $currentModule = null;
        $currentSection = 'description';

        foreach (explode("\n", $content) as $line) {
            $line = trim(strip_tags($line));

            if ($line === '') {
                continue;
            }

            if (preg_match('/^Module\s+(\d+)[:\s]\s*(.+)$/i', $line, $matches)) {
                if ($currentModule) {
                    $modules[] = $this->trimModuleFields($currentModule);
                }
                $currentModule = [
                    'module_number'       => (int) $matches[1],
                    'title'               => trim($matches[2]),
                    'short_description'   => '',
                    'learning_objectives' => '',
                    'topics_covered'      => '',
                    'full_content'        => '',
                    'estimated_hours'     => 2,
                ];
                $currentSection = 'description';
                continue;
            }

            if (preg_match('/^(Objectives|Topics|Case\s+Study|Exercise|Key\s+Concepts)\s*:$/i', $line, $matches)) {
                $currentSection = strtolower(str_replace([' ', '-'], '_', $matches[1]));
                continue;
            }

            if ($currentModule === null) {
                continue;
            }

            switch ($currentSection) {
                case 'description':
                    $currentModule['short_description'] .= $line . "\n";
                    $currentModule['full_content']      .= $line . "\n";
                    break;
                case 'objectives':
                    $currentModule['learning_objectives'] .= $line . "\n";
                    break;
                case 'topics':
                    $currentModule['topics_covered'] .= $line . "\n";
                    break;
                default:
                    // key_concepts, case_study, exercise go into full_content
                    $currentModule['full_content'] .= $line . "\n";
            }
        }

        if ($currentModule) {
            $modules[] = $this->trimModuleFields($currentModule);
        }

        return $modules;
    }

    private function trimModuleFields(array $module): array
    {
        foreach (['short_description', 'learning_objectives', 'topics_covered', 'full_content'] as $field) {
            $module[$field] = trim($module[$field] ?? '');
        }
        return $module;
    }

    // -------------------------------------------------------------------------
    //  Materials upload
    // -------------------------------------------------------------------------

        public function uploadMaterials(Request $request, Course $course)
        {
            $request->validate([
                'materials.*'   => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar|max:10240',
                'material_type' => 'required|in:manual,presentation,worksheet,template,reference',
                'module_id'     => 'nullable|exists:course_modules,id',
            ]);

            try {
                $uploaded = [];

                foreach ($request->file('materials') as $file) {
                    $path = $file->store('courses/materials', 'public');

                    $material = $course->materials()->create([
                        'module_id'     => $request->module_id,
                        'title'         => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        'file_name'     => $file->getClientOriginalName(),
                        'file_path'     => $path,
                        'file_type'     => $file->getClientOriginalExtension(),
                        'file_size'     => $file->getSize(),
                        'material_type' => $request->material_type,
                        'is_downloadable' => $request->has('is_downloadable'),
                    ]);

                    $uploaded[] = $material;
                }

                return back()->with('success', count($uploaded) . ' materials uploaded successfully!');

            } catch (\Exception $e) {
                return back()->with('error', 'Error uploading materials: ' . $e->getMessage());
            }
        }

   public function materialsUpload(Request $request, $courseIdentifier)
    {
        $course = Course::where('id', $courseIdentifier)
            ->orWhere('slug', $courseIdentifier)
            ->firstOrFail();

        $request->validate([
            'materials.*'   => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar|max:10240',
            'material_type' => 'required|in:manual,presentation,worksheet,template,reference',
            'module_id'     => 'nullable|exists:course_modules,id',  // ← fixed table name
        ]);

        try {
            $uploaded = 0;

            foreach ($request->file('materials') as $file) {
                $path = $file->store('courses/materials', 'public');

                $course->materials()->create([
                    'module_id'       => $request->module_id,
                    'title'           => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'file_name'       => $file->getClientOriginalName(),
                    'file_path'       => $path,
                    'file_type'       => $file->getClientOriginalExtension(),
                    'file_size'       => $file->getSize(),
                    'material_type'   => $request->material_type,
                    'is_downloadable' => $request->has('is_downloadable'),
                ]);

                $uploaded++;
            }

            return redirect()
                ->route('admin.courses.show', $course->slug)
                ->with('success', $uploaded . ' material(s) uploaded successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error uploading materials: ' . $e->getMessage());
        }
    }

    private function processMaterialUpload($file, $course, $request, $index = 0)
    {
        $originalName  = $file->getClientOriginalName();
        $extension     = $file->getClientOriginalExtension();
        $filename      = pathinfo($originalName, PATHINFO_FILENAME);
        $safeFilename  = Str::slug($filename);
        $newFilename   = $safeFilename . '_' . time() . '_' . Str::random(5) . '.' . $extension;
        $year          = date('Y');
        $month         = date('m');
        $storagePath   = "courses/materials/{$course->id}/{$year}/{$month}";
        $path          = $file->storeAs($storagePath, $newFilename, 'public');
        $fileType      = $file->getClientMimeType();
        $fileSize      = $file->getSize();

        $thumbnailPath = null;
        if (str_contains($fileType, 'image')) {
            $thumbnailPath = $this->generateThumbnail($file, $storagePath, $newFilename);
        }

        return CourseMaterial::create([
            'course_id'         => $course->id,
            'module_id'         => $request->module_id,
            'title'             => $request->input("titles.{$index}", $filename),
            'description'       => $request->input("descriptions.{$index}", ''),
            'original_filename' => $originalName,
            'file_path'         => $path,
            'thumbnail_path'    => $thumbnailPath,
            'file_size'         => $fileSize,
            'file_type'         => $fileType,
            'extension'         => $extension,
            'material_type'     => $request->material_type,
            'is_downloadable'   => $request->boolean('is_downloadable'),
            'uploaded_by'       => auth()->id(),
            'uploaded_at'       => now(),
            'download_count'    => 0,
            'view_count'        => 0,
        ]);
    }

    private function handleUploadResponse($request, $results, $course)
    {
        $successCount = count($results['success']);
        $failedCount  = count($results['failed']);

        if ($request->ajax()) {
            return response()->json([
                'success'       => true,
                'message'       => "{$successCount} file(s) uploaded successfully",
                'success_count' => $successCount,
                'failed_count'  => $failedCount,
                'failed_files'  => $results['failed'],
                'redirect'      => route('admin.courses.show', $course->slug),
            ]);
        }

        $message = "{$successCount} file(s) uploaded successfully!";

        if ($failedCount > 0) {
            $failedNames = implode(', ', array_column($results['failed'], 'name'));
            $message    .= " {$failedCount} file(s) failed: {$failedNames}";
            return redirect()->route('admin.courses.show', $course->slug)->with('warning', $message);
        }

        return redirect()->route('admin.courses.show', $course->slug)->with('success', $message);
    }

    // -------------------------------------------------------------------------
    //  Module management
    // -------------------------------------------------------------------------

    public function createModule(Course $course)
    {
        return view('admin.courses.modules.create', compact('course'));
    }

    public function storeModule(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'module_number'       => 'required|integer|min:1',
            'short_description'   => 'required|string',
            'full_content'        => 'required|string',
            'learning_objectives' => 'nullable|string',
            'key_concepts'        => 'nullable|string',
            'topics_covered'      => 'nullable|string',
            'case_study'          => 'nullable|string',
            'exercise'            => 'nullable|string',
            'additional_notes'    => 'nullable|string',
            'estimated_hours'     => 'required|integer|min:1',
        ]);

        $course->modules()->create($validated);

        return redirect()->route('admin.courses.show', $course->slug)
            ->with('success', 'Module created successfully!');
    }

    // -------------------------------------------------------------------------
    //  Document import (unchanged)
    // -------------------------------------------------------------------------

    public function importFromDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:txt,doc,docx,pdf|max:10240',
        ]);

        try {
            $content    = $this->extractDocumentContent($request->file('document'));
            $courseData = $this->parseCourseDocument($content);
            session()->flash('import_preview', $courseData);
            return view('admin.courses.import-preview', $courseData);
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing document: ' . $e->getMessage());
        }
    }

    private function extractDocumentContent($file): string
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension === 'txt') {
            return file_get_contents($file->getRealPath());
        }

        throw new \Exception("Unsupported file format for extraction: {$extension}");
    }

    private function parseCourseDocument(string $content): array
    {
        $data = ['title' => '', 'modules' => [], 'programme_overview' => '', 'learning_outcomes' => []];
        $lines = explode("\n", $content);
        $currentSection = '';

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (str_contains($line, 'Certified GRC & Financial Crime Specialist')) {
                $data['title'] = $line;
            }

            if (str_starts_with($line, 'Programme Overview') || str_starts_with($line, '1. Programme Overview')) {
                $currentSection = 'overview';
            } elseif (str_starts_with($line, 'Learning Outcomes')) {
                $currentSection = 'outcomes';
            } elseif (str_starts_with($line, 'Module')) {
                $currentSection = 'module';
                $moduleData = $this->extractModuleData($line);
                if ($moduleData) {
                    $data['modules'][] = $moduleData;
                }
            }

            if ($currentSection === 'overview' && !str_starts_with($line, 'Programme Overview')) {
                $data['programme_overview'] .= $line . "\n";
            } elseif ($currentSection === 'outcomes' && !str_starts_with($line, 'Learning Outcomes')) {
                if (str_starts_with($line, '-') || str_starts_with($line, '•')) {
                    $data['learning_outcomes'][] = trim($line, "-• \t\n\r\0\x0B");
                }
            }
        }

        return $data;
    }

    private function extractModuleData(string $line): ?array
    {
        if (preg_match('/Module (\d+): (.+)/', $line, $matches)) {
            return ['module_number' => (int) $matches[1], 'title' => $matches[2]];
        }
        return null;
    }

    // -------------------------------------------------------------------------
    //  Helpers
    // -------------------------------------------------------------------------

    private function uploadFile($file, string $directory): string
    {
        $filename = $directory . '/' . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public', $filename);
        return $filename;
    }
}