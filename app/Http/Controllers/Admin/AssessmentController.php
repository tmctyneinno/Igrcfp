<?php
// app/Http/Controllers/Admin/AssessmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\AssessmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssessmentController extends Controller
{
    /**
     * Show all assessments (no course filter)
     */
    public function all()
    {
        $assessments = Assessment::with('course')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $allCourses = Course::all();
        
        $statistics = [
            'total' => Assessment::count(),
            'active' => Assessment::where('status', 'active')->count(),
            'submissions' => AssessmentSubmission::count(),
            'pending_grading' => AssessmentSubmission::where('status', 'submitted')->count(),
        ];
        
        return view('admin.courses.assessments.index', compact('assessments', 'allCourses', 'statistics'));
    }
    
    /**
     * Show assessments for a specific course
     */
    public function index(Course $course)
    {
        $assessments = Assessment::where('course_id', $course->id)
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $allCourses = Course::all();
        
        $statistics = [
            'total' => Assessment::where('course_id', $course->id)->count(),
            'active' => Assessment::where('course_id', $course->id)->where('status', 'active')->count(),
            'submissions' => AssessmentSubmission::whereHas('assessment', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })->count(),
            'pending_grading' => AssessmentSubmission::whereHas('assessment', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })->where('status', 'submitted')->count(),
        ];
        
        return view('admin.courses.assessments.index', compact('assessments', 'course', 'allCourses', 'statistics'));
    }

    /**
     * Show the form for creating a new assessment.
     */
    public function create()
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        
        return view('admin.courses.assessments.create', compact('courses'));
    }

    /**
     * Store a newly created assessment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:exam,assignment,quiz,project',
            'status' => 'required|in:draft,active,archived',
            'duration' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|integer|min:1',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable',
            'release_date' => 'nullable|date',
            'release_time' => 'nullable',
            'is_timed' => 'boolean',
            'needs_manual_marking' => 'boolean',
            'allow_late_submissions' => 'boolean',
            'assessment_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,zip|max:51200', // 50MB max
        ]);

        // Handle file upload
        if ($request->hasFile('assessment_file')) {
            $file = $request->file('assessment_file');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('assessments/' . $validated['course_id'], $filename, 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        // Combine date and time for due_date
        if ($request->filled('due_date')) {
            $dueDateTime = $request->due_date;
            if ($request->filled('due_time')) {
                $dueDateTime .= ' ' . $request->due_time;
            }
            $validated['due_date'] = date('Y-m-d H:i:s', strtotime($dueDateTime));
        }

        // Combine date and time for release_date
        if ($request->filled('release_date')) {
            $releaseDateTime = $request->release_date;
            if ($request->filled('release_time')) {
                $releaseDateTime .= ' ' . $request->release_time;
            }
            $validated['release_date'] = date('Y-m-d H:i:s', strtotime($releaseDateTime));
        }

        // Set boolean values
        $validated['is_timed'] = $request->has('is_timed');
        $validated['needs_manual_marking'] = $request->has('needs_manual_marking');
        $validated['allow_late_submissions'] = $request->has('allow_late_submissions');

        Assessment::create($validated);

        return redirect()->route('admin.courses.assessments.index', $validated['course_id'])
            ->with('success', 'Assessment created successfully!');
    }

    /**
     * Display the specified assessment.
     */
    public function show(Assessment $assessment)
    {
        $assessment->load('course', 'submissions.user');
        
        return view('admin.assessments.show', compact('assessment'));
    }

    /**
     * Show the form for editing the specified assessment.
     */
    public function edit(Assessment $assessment)
    {
        $courses = Course::where('status', 'published')->orderBy('title')->get();
        
        return view('admin.assessments.edit', compact('assessment', 'courses'));
    }

    /**
     * Update the specified assessment in storage.
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:exam,assignment,quiz,project',
            'status' => 'required|in:draft,active,archived',
            'duration' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|integer|min:1',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable',
            'release_date' => 'nullable|date',
            'release_time' => 'nullable',
            'is_timed' => 'boolean',
            'needs_manual_marking' => 'boolean',
            'allow_late_submissions' => 'boolean',
            'assessment_file' => 'nullable|file|mimes:pdf,doc,docx,xlsx,zip|max:51200',
        ]);

        // Handle file upload
        if ($request->hasFile('assessment_file')) {
            // Delete old file if exists
            if ($assessment->file_path) {
                Storage::disk('public')->delete($assessment->file_path);
            }
            
            $file = $request->file('assessment_file');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('assessments/' . $validated['course_id'], $filename, 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
        }

        // Combine date and time for due_date
        if ($request->filled('due_date')) {
            $dueDateTime = $request->due_date;
            if ($request->filled('due_time')) {
                $dueDateTime .= ' ' . $request->due_time;
            }
            $validated['due_date'] = date('Y-m-d H:i:s', strtotime($dueDateTime));
        }

        // Combine date and time for release_date
        if ($request->filled('release_date')) {
            $releaseDateTime = $request->release_date;
            if ($request->filled('release_time')) {
                $releaseDateTime .= ' ' . $request->release_time;
            }
            $validated['release_date'] = date('Y-m-d H:i:s', strtotime($releaseDateTime));
        }

        // Set boolean values
        $validated['is_timed'] = $request->has('is_timed');
        $validated['needs_manual_marking'] = $request->has('needs_manual_marking');
        $validated['allow_late_submissions'] = $request->has('allow_late_submissions');

        $assessment->update($validated);

        return redirect()->route('admin.assessments.index', $assessment->course_id)
            ->with('success', 'Assessment updated successfully!');
    }

    /**
     * Remove the specified assessment from storage.
     */
    public function destroy(Assessment $assessment)
    {
        // Delete file if exists
        if ($assessment->file_path) {
            Storage::disk('public')->delete($assessment->file_path);
        }
        
        // Delete associated submissions
        $assessment->submissions()->delete();
        
        $courseId = $assessment->course_id;
        $assessment->delete();

        return redirect()->route('admin.assessments.index', $courseId)
            ->with('success', 'Assessment deleted successfully!');
    }

    /**
     * Upload assessment for a specific course (from modal)
     */
    public function upload(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:exam,assignment,quiz,project',
            'status' => 'required|in:draft,active,archived',
            'duration' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|integer|min:1',
            'weight' => 'nullable|integer|min:1|max:100',
            'due_date' => 'nullable|date',
            'due_time' => 'nullable',
            'is_timed' => 'boolean',
            'needs_manual_marking' => 'boolean',
            'assessment_file' => 'required|file|mimes:pdf,doc,docx,xlsx,zip|max:51200',
        ]);

        // Handle file upload
        $file = $request->file('assessment_file');
        $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('assessments/' . $course->id, $filename, 'public');

        // Combine date and time for due_date
        if ($request->filled('due_date')) {
            $dueDateTime = $request->due_date;
            if ($request->filled('due_time')) {
                $dueDateTime .= ' ' . $request->due_time;
            }
            $dueDate = date('Y-m-d H:i:s', strtotime($dueDateTime));
        }

        Assessment::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'status' => $validated['status'],
            'duration' => $validated['duration'] ?? null,
            'total_marks' => $validated['total_marks'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'due_date' => $dueDate ?? null,
            'is_timed' => $request->has('is_timed'),
            'needs_manual_marking' => $request->has('needs_manual_marking'),
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('admin.assessments.index', $course->id)
            ->with('success', 'Assessment uploaded successfully!');
    }

    /**
     * Get submissions for an assessment (AJAX)
     */
    public function submissions(Assessment $assessment)
    {
        $submissions = $assessment->submissions()
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'student_name' => $submission->user->name ?? 'N/A',
                    'candidate_id' => $submission->user->candidate_id ?? 'N/A',
                    'submitted_at' => $submission->submitted_at,
                    'score' => $submission->score,
                    'status' => $submission->status,
                ];
            });

        return response()->json(['submissions' => $submissions]);
    }

    /**
     * View a specific submission
     */
    public function viewSubmission(AssessmentSubmission $submission)
    {
        $submission->load('user', 'assessment.course');
        
        return view('admin.assessments.submission', compact('submission'));
    }

    /**
     * Grade a submission
     */
    public function gradeSubmission(Request $request, AssessmentSubmission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . ($submission->assessment->total_marks ?? 100),
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'graded_at' => now(),
            'graded_by' => auth()->id(),
            'status' => 'graded',
        ]);

        return redirect()->back()->with('success', 'Submission graded successfully!');
    }
}