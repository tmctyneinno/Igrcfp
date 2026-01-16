<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::latest()->paginate(10);
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
            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $this->uploadImage($request->file('image'));
            }

            // Handle video upload
            if ($request->video_type === 'upload' && $request->hasFile('video')) {
                $validated['video'] = $this->uploadVideo($request->file('video'));
            }

            // Create course
            Course::create($validated);

            return redirect()->route('admin.courses.index')
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
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $this->validateRequest($request, $course);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($course->image) {
                    Storage::delete($course->image);
                }
                $validated['image'] = $this->uploadImage($request->file('image'));
            }

            // Handle video upload
            if ($request->video_type === 'upload' && $request->hasFile('video')) {
                // Delete old video if exists
                if ($course->video) {
                    Storage::delete($course->video);
                }
                $validated['video'] = $this->uploadVideo($request->file('video'));
            } elseif ($request->video_type !== 'upload') {
                // Delete video file if switching to URL-based video
                if ($course->video) {
                    Storage::delete($course->video);
                    $validated['video'] = null;
                }
            }

            // Update course
            $course->update($validated);

            return redirect()->route('admin.courses.index')
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
            if ($course->image) {
                Storage::delete($course->image);
            }
            if ($course->video) {
                Storage::delete($course->video);
            }

            $course->delete();

            return redirect()->route('admin.courses.index')
                ->with('success', 'Course deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting course: ' . $e->getMessage());
        }
    }

    /**
     * Validate the request data
     */
    private function validateRequest(Request $request, ?Course $course = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'short_title' => 'required|string|max:100',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_type' => 'required|in:none,upload,youtube,vimeo',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:20480', // 20MB
            'video_url' => 'nullable|url|max:500',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'format' => 'required|in:self_paced,instructor_led,hybrid',
            'duration' => 'required|string|max:100',
            'modules_count' => 'required|integer|min:1',
            'completed_modules' => 'nullable|integer|min:0',
            'certification' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
            'learning_outcomes' => 'nullable|string',
            'prerequisites' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
        ];

        // Conditional validation for video
        if ($request->video_type === 'upload') {
            $rules['video'] = 'required|file|mimes:mp4,mov,avi,wmv,mkv|max:20480';
        } elseif (in_array($request->video_type, ['youtube', 'vimeo'])) {
            $rules['video_url'] = 'required|url';
        }

        // Conditional validation for completed modules
        $request->validate([
            'completed_modules' => 'lte:modules_count',
        ]);

        return $request->validate($rules);
    }

    /**
     * Upload image to storage
     */
    private function uploadImage($file): string
    {
        $filename = 'courses/images/' . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public', $filename);
        return 'storage/' . $filename;
    }

    /**
     * Upload video to storage
     */
    private function uploadVideo($file): string
    {
        $filename = 'courses/videos/' . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public', $filename);
        return 'storage/' . $filename;
    }

    /**
     * Update course status
     */
    public function updateStatus(Request $request, Course $course)
    {
        $request->validate([
            'status' => 'required|in:draft,published,archived'
        ]);

        $course->update(['status' => $request->status]);

        return back()->with('success', 'Course status updated!');
    }

    /**
     * Toggle course feature status
     */
    public function toggleFeatured(Course $course)
    {
        $course->update(['is_featured' => !$course->is_featured]);

        return back()->with('success', 'Course feature status updated!');
    }

    /**
     * Toggle course popularity status
     */
    public function togglePopular(Course $course)
    {
        $course->update(['is_popular' => !$course->is_popular]);

        return back()->with('success', 'Course popularity status updated!');
    }
}