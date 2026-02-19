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
use DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allCourses = Course::all();
        $query = Course::withCount('modules');
    
        $courses = $query->get();
        $totalCourses = Course::count();
        $query = Course::withCount('modules');
       
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('short_title', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%")
                ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by level
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }
         
        // Order by
        $query->latest();
        
        // Pagination - REMOVED the early $query->get() call
        $perPage = $request->get('per_page', 10);
        $courses = $query->paginate($perPage);
        
        return view('admin.courses.index', compact('courses'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,draft,archive,delete',
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id'
        ]);
        
        $action = $request->action;
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
        }
        
        return redirect()->route('admin.courses.index')
            ->with('success', $message);
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
        \Log::info('Course Store Request - Full Details:', [
            'input' => $request->except(['_token', '_method']),
            
        ]);

        $validated = $this->validateRequest($request);
        \Log::info('Course validated: ', $validated);
 

        try {
            // Handle image uploads
            
             if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('courses/images', 'public');
            }
            
            if ($request->hasFile('banner_image')) {
                $validated['banner_image'] = $request->file('banner_image')->store('courses/banner', 'public');
            }

            // Handle video upload
            if ($request->video_type === 'upload' && $request->hasFile('video')) {
                $validated['video'] = $request->file('video')->store('courses/videos', 'public');
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

            return redirect()->route('admin.courses.show', $course->slug)
                ->with('success', 'Course created successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating course: ' . $e->getMessage());
        }
    }

    private function validateRequest(Request $request, ?Course $course = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'short_title' => 'required|string|max:100',
            'short_description' => 'required|string',
            'full_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'video_type' => 'nullable|in:none,upload,youtube,vimeo',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:20480',
            'video_url' => 'nullable|url|max:500',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'format' => 'required|in:self_paced,instructor_led,hybrid',
            'duration' => 'required|string|max:100',
            'total_modules' => 'required|integer|min:1',
            'total_hours' => 'required|integer|min:1',
            'certification_name' => 'required|string',
            'certifying_body' => 'required|string',
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
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
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
    public function update(Request $request, $slug)
    { 
        \Log::info('Update method called for course: ' . $slug);
        \Log::info('Request data:', $request->all());
        // dd('update_Course');
        if (is_numeric($slug)) {
        $course = Course::findOrFail($slug);
        } else {
            $course = Course::where('slug', $slug)->firstOrFail();
        }
       
        // dd($id);
      
        $validated = $this->validateRequest($request, $course);
        unset($validated['deleted_at']);


        try {
            // Handle image uploads
           
            if ($request->hasFile('image')) {
                if ($course->image) {
                    Storage::delete($course->image);
                }
                $validated['image'] = $request->file('image')->store('courses/images', 'public');
            }
            
            if ($request->hasFile('banner_image')) {
                if ($course->banner_image) {
                    Storage::delete($course->banner_image);
                }
                $validated['banner_image'] = $request->file('banner_image')->store('courses/banner', 'public');
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
                // $lines = explode("\n", $request->target_audience);
                // $validated['target_audience'] = array_map('trim', array_filter($lines));
                $validated['target_audience'] = $request->target_audience;
            }

            // Update course
            $course->update($validated);

            // Handle bulk modules update if provided
            if ($request->filled('bulk_modules')) {
                $this->processBulkModules($course, $request->bulk_modules, true);
            }

            return redirect()->route('admin.courses.show', $course->slug)
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
                // $path = $this->uploadFile($file, 'courses/materials');
                $path = $request->file('video')->store('courses/materials', 'public');
                
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

    public function materialsUpload(Request $request, $courseIdentifier)
    {
        // Find course
        $course = Course::where('id', $courseIdentifier)
            ->orWhere('slug', $courseIdentifier)
            ->firstOrFail();
 
        // Validate
        $request->validate([
            'materials.*' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,csv,txt,zip,rar,mp4,avi,mov,wmv,mp3,wav,jpg,jpeg,png,gif,bmp,svg|max:20480', // 20MB
            'material_type' => 'required|string|in:manual,presentation,worksheet,template,reference,video,audio,image,other',
            'module_id' => 'nullable|exists:modules,id',
            'is_downloadable' => 'boolean',
            'description' => 'nullable|string|max:500'
        ]);
          
        // Handle uploads
        $results = [
            'success' => [],
            'failed' => []
        ];
           
        DB::beginTransaction();
        
        try {
            foreach ($request->file('materials') as $index => $file) {
                try {
                     
                    // Process each file
                    $material = $this->processMaterialUpload($file, $course, $request, $index);
                    
                    $results['success'][] = [
                        'name' => $material->original_filename,
                        'id' => $material->id,
                        'url' => Storage::url($material->file_path)
                    ];
                    
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'name' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ];
                }
            }
            
            DB::commit();
            
            // Log the upload activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'upload_materials',
                'description' => 'Uploaded ' . count($results['success']) . ' materials to course: ' . $course->title,
                'details' => json_encode([
                    'course_id' => $course->id,
                    'materials_count' => count($results['success']),
                    'material_ids' => array_column($results['success'], 'id')
                ])
            ]);
            
            // Return appropriate response
            return $this->handleUploadResponse($request, $results, $course);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    private function processMaterialUpload($file, $course, $request, $index = 0)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $safeFilename = Str::slug($filename);
        // Create unique filename
        $newFilename = $safeFilename . '_' . time() . '_' . Str::random(5) . '.' . $extension;
        
        // Determine storage path
        $year = date('Y');
        $month = date('m');
        $storagePath = "courses/materials/{$course->id}/{$year}/{$month}";
        
        // Store file
        $path = $file->storeAs($storagePath, $newFilename, 'public');
        
        // Get additional file info
        $fileType = $file->getClientMimeType();
        $fileSize = $file->getSize();
        
        // Generate thumbnail for images
        $thumbnailPath = null;
        if (str_contains($fileType, 'image')) {
            $thumbnailPath = $this->generateThumbnail($file, $storagePath, $newFilename);
        }
        
        
        // Create material record
        return CourseMaterial::create([
            'course_id' => $course->id,
            'module_id' => $request->module_id,
            'title' => $request->input("titles.{$index}", $filename),
            'description' => $request->input("descriptions.{$index}", ''),
            'original_filename' => $originalName,
            'file_path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'extension' => $extension,
            'material_type' => $request->material_type,
            'is_downloadable' => $request->boolean('is_downloadable'),
            'uploaded_by' => auth()->id(),
            'uploaded_at' => now(),
            'download_count' => 0,
            'view_count' => 0,
        ]);
    }

    private function handleUploadResponse($request, $results, $course)
    {
        $successCount = count($results['success']);
        $failedCount = count($results['failed']);
        
        // If AJAX request (for progress bar)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$successCount} file(s) uploaded successfully",
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'failed_files' => $results['failed'],
                'redirect' => route('admin.courses.show', $course->slug)
            ]);
        }
        
        // Regular HTTP request
        $message = "{$successCount} file(s) uploaded successfully!";
        
        if ($failedCount > 0) {
            $failedNames = implode(', ', array_column($results['failed'], 'name'));
            $message .= " {$failedCount} file(s) failed: {$failedNames}";
            
            return redirect()->route('admin.courses.show', $course->slug)
                ->with('warning', $message);
        }
        
        return redirect()->route('admin.courses.show', $course->slug)
            ->with('success', $message);
    }
}