<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::withCount('modules')
            ->latest()
            ->paginate(10);
        
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        try {
            // Handle image uploads
            if ($request->hasFile('image')) {
                $validated['image'] = $this->uploadFile($request->file('image'), 'courses/images');
            }
            
            if ($request->hasFile('banner_image')) {
                $validated['banner_image'] = $this->uploadFile($request->file('banner_image'), 'courses/banners');
            }

            // Handle video upload
            if ($request->video_type === 'upload' && $request->hasFile('video')) {
                $validated['video'] = $this->uploadFile($request->file('video'), 'courses/videos');
            }

            // Convert target audience from textarea to array
            if ($request->filled('target_audience')) {
                $lines = explode("\n", $request->target_audience);
                $validated['target_audience'] = array_map('trim', array_filter($lines));
            }

            // Create course
            $course = Course::create($validated);

            // Handle bulk modules upload if provided
            if ($request->filled('bulk_modules')) {
                $this->processBulkModules($course, $request->bulk_modules);
            }

            // Handle materials upload
            if ($request->hasFile('materials')) {
                $this->uploadMaterials($course, $request->file('materials'));
            }

            return redirect()->route('admin.courses.show', $course->id)
                ->with('success', 'Course created successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating course: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['modules.sections', 'materials']);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $course->load(['modules', 'materials']);
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $this->validateRequest($request, $course);

        try {
            // Handle image uploads
            if ($request->hasFile('image')) {
                if ($course->image) {
                    Storage::delete($course->image);
                }
                $validated['image'] = $this->uploadFile($request->file('image'), 'courses/images');
            }
            
            if ($request->hasFile('banner_image')) {
                if ($course->banner_image) {
                    Storage::delete($course->banner_image);
                }
                $validated['banner_image'] = $this->uploadFile($request->file('banner_image'), 'courses/banners');
            }

            // Handle video upload
            if ($request->video_type === 'upload' && $request->hasFile('video')) {
                if ($course->video) {
                    Storage::delete($course->video);
                }
                $validated['video'] = $this->uploadFile($request->file('video'), 'courses/videos');
            } elseif ($request->video_type !== 'upload') {
                if ($course->video) {
                    Storage::delete($course->video);
                    $validated['video'] = null;
                }
            }

            // Convert target audience from textarea to array
            if ($request->filled('target_audience')) {
                $lines = explode("\n", $request->target_audience);
                $validated['target_audience'] = array_map('trim', array_filter($lines));
            }

            // Update course
            $course->update($validated);

            // Handle bulk modules update if provided
            if ($request->filled('bulk_modules')) {
                $this->processBulkModules($course, $request->bulk_modules, true);
            }

            return redirect()->route('admin.courses.show', $course->id)
                ->with('success', 'Course updated successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating course: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            // Delete associated files
            $files = [
                $course->image,
                $course->banner_image,
                $course->video,
            ];
            
            foreach ($files as $file) {
                if ($file) {
                    Storage::delete($file);
                }
            }
            
            // Delete materials
            foreach ($course->materials as $material) {
                Storage::delete($material->file_path);
            }

            $course->delete();

            return redirect()->route('admin.courses.index')
                ->with('success', 'Course deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting course: ' . $e->getMessage());
        }
    }

    /**
     * Upload materials for a course
     */
    public function uploadMaterials(Request $request, Course $course)
    {
        $request->validate([
            'materials.*' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar|max:10240',
            'material_type' => 'required|in:manual,presentation,worksheet,template,reference',
            'module_id' => 'nullable|exists:course_modules,id',
        ]);

        try {
            $uploaded = [];
            
            foreach ($request->file('materials') as $file) {
                $path = $this->uploadFile($file, 'courses/materials');
                
                $material = $course->materials()->create([
                    'module_id' => $request->module_id,
                    'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
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

    /**
     * Bulk import modules from formatted text
     */
    private function processBulkModules(Course $course, string $bulkContent, bool $replace = false)
    {
        // Parse the bulk content (assuming a specific format)
        $modules = $this->parseBulkModules($bulkContent);
        
        if ($replace) {
            $course->modules()->delete();
        }
        
        foreach ($modules as $moduleData) {
            $course->modules()->create($moduleData);
        }
    }

    /**
     * Parse bulk modules from text format
     * Expected format:
     * Module X: Title
     * Description...
     * 
     * Objectives:
     * - Objective 1
     * - Objective 2
     * 
     * Topics:
     * - Topic 1
     * - Topic 2
     */
    private function parseBulkModules(string $content): array
    {
        $modules = [];
        $lines = explode("\n", $content);
        $currentModule = null;
        $currentSection = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) continue;
            
            // Check for module header
            if (preg_match('/^Module\s+(\d+):\s*(.+)$/i', $line, $matches)) {
                if ($currentModule) {
                    $modules[] = $currentModule;
                }
                
                $currentModule = [
                    'module_number' => (int) $matches[1],
                    'title' => $matches[2],
                    'short_description' => '',
                    'learning_objectives' => '',
                    'topics_covered' => '',
                    'full_content' => '',
                    'estimated_hours' => 2, // Default
                ];
                $currentSection = 'description';
            }
            // Check for section headers
            elseif (preg_match('/^(Objectives|Topics|Case Study|Exercise|Key Concepts):$/i', $line, $matches)) {
                $currentSection = strtolower(str_replace(' ', '_', $matches[1]));
            }
            // Add content to current section
            elseif ($currentModule) {
                if ($currentSection === 'description') {
                    $currentModule['short_description'] .= $line . "\n";
                    $currentModule['full_content'] .= $line . "\n";
                } elseif (isset($currentModule[$currentSection])) {
                    $currentModule[$currentSection] .= $line . "\n";
                }
            }
        }
        
        // Add the last module
        if ($currentModule) {
            $modules[] = $currentModule;
        }
        
        return $modules;
    }

    /**
     * Validate the request data
     */
    private function validateRequest(Request $request, ?Course $course = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('courses')->ignore($course?->id),
            ],
            'short_title' => 'required|string|max:100',
            'short_description' => 'required|string|max:500',
            'full_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_type' => 'required|in:none,upload,youtube,vimeo',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:20480',
            'video_url' => 'nullable|url|max:500',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'format' => 'required|in:self_paced,instructor_led,hybrid',
            'duration' => 'required|string|max:100',
            'total_modules' => 'required|integer|min:1',
            'total_hours' => 'required|integer|min:1',
            'certification_name' => 'required|string|max:255',
            'certifying_body' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'target_audience' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'prerequisites' => 'nullable|string',
            'career_pathways' => 'nullable|string',
            'assessment_structure' => 'nullable|string',
            'code_of_conduct' => 'nullable|string',
            'programme_overview' => 'nullable|string',
            'programme_architecture' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'sort_order' => 'nullable|integer',
            'bulk_modules' => 'nullable|string',
        ];

        // Conditional validation for video
        if ($request->video_type === 'upload') {
            $rules['video'] = 'required|file|mimes:mp4,mov,avi,wmv,mkv|max:20480';
        } elseif (in_array($request->video_type, ['youtube', 'vimeo'])) {
            $rules['video_url'] = 'required|url';
        }

        return $request->validate($rules);
    }

    /**
     * Upload file to storage
     */
    private function uploadFile($file, string $directory): string
    {
        $filename = $directory . '/' . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public', $filename);
        return $filename;
    }

    /**
     * Additional methods for module management
     */
    public function createModule(Course $course)
    {
        return view('admin.courses.modules.create', compact('course'));
    }

    public function storeModule(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'module_number' => 'required|integer|min:1',
            'short_description' => 'required|string',
            'full_content' => 'required|string',
            'learning_objectives' => 'nullable|string',
            'key_concepts' => 'nullable|string',
            'topics_covered' => 'nullable|string',
            'case_study' => 'nullable|string',
            'exercise' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'estimated_hours' => 'required|integer|min:1',
        ]);

        $course->modules()->create($validated);

        return redirect()->route('admin.courses.show', $course->id)
            ->with('success', 'Module created successfully!');
    }

    /**
     * Import course from formatted document
     */
    public function importFromDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:txt,doc,docx,pdf|max:10240',
        ]);

        try {
            $content = $this->extractDocumentContent($request->file('document'));
            $courseData = $this->parseCourseDocument($content);
            
            // Store in session for preview
            session()->flash('import_preview', $courseData);
            
            return view('admin.courses.import-preview', $courseData);

        } catch (\Exception $e) {
            return back()->with('error', 'Error importing document: ' . $e->getMessage());
        }
    }

    /**
     * Extract text from various document formats
     */
    private function extractDocumentContent($file): string
    {
        $extension = $file->getClientOriginalExtension();
        
        if ($extension === 'txt') {
            return file_get_contents($file->getRealPath());
        } elseif (in_array($extension, ['doc', 'docx'])) {
            // Use PHPWord or similar library
            // For simplicity, returning placeholder
            return "Document content extraction for .{$extension} files requires additional libraries.";
        } elseif ($extension === 'pdf') {
            // Use Smalot\PdfParser or similar library
            // For simplicity, returning placeholder
            return "Document content extraction for .pdf files requires additional libraries.";
        }
        
        throw new \Exception("Unsupported file format: {$extension}");
    }

    /**
     * Parse course document content
     */
    private function parseCourseDocument(string $content): array
    {
        // This is a simplified parser - you'll need to customize based on your document format
        $data = [
            'title' => '',
            'code' => '',
            'modules' => [],
            'programme_overview' => '',
            'learning_outcomes' => [],
        ];
        
        $lines = explode("\n", $content);
        $currentSection = '';
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) continue;
            
            // Extract course title and code
            if (str_contains($line, 'Certified GRC & Financial Crime Specialist')) {
                $data['title'] = $line;
                $data['code'] = 'CGFCS';
            }
            
            // Extract programme overview
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
            
            // Add content to current section
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
            return [
                'module_number' => (int) $matches[1],
                'title' => $matches[2],
            ];
        }
        return null;
    }
}