<?php

namespace App\Http\Controllers;

use App\Models\MentorProfile;
use App\Services\ActivityLoggerService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorController extends Controller
{
    public function index(Request $request)
    {
        $query = MentorProfile::query()
            ->with('user')
            ->where('is_active', true)
            ->where('user_id', '!=', $request->user()->id);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('domain', 'like', "%{$search}%");
            });
        }

        // Apply region filter
        if ($request->filled('region')) {
            $query->where('region', $request->string('region'));
        }

        // Apply country filter
        if ($request->filled('country')) {
            $query->where('country', $request->string('country'));
        }

        // Apply availability filter
        if ($request->filled('availability')) {
            $query->where('availability_status', $request->string('availability'));
        }

        $mentors = $query->orderBy('rating', 'desc')
            ->paginate(12)
            ->withQueryString()
            ->through(function ($mentor) {
                return [
                    'id' => $mentor->id,
                    'name' => $mentor->user->name,
                    'title' => $mentor->title,
                    'domain' => $mentor->domain,
                    'region' => $mentor->region,
                    'country' => $mentor->country,
                    'availability_status' => $mentor->availability_status,
                    'rating' => $mentor->rating,
                    'completed' => $mentor->completed_mentorships_count,
                    'slots_left' => $mentor->remainingCapacity(),
                ];
            });

        // Log mentor directory browse
        ActivityLoggerService::log(
            ActivityLog::EVENT_UPDATED,
            'mentors',
            'Browsed mentor directory',
            "User browsed mentor directory",
            null,
            [
                'user_id' => $request->user()->id,
                'filters_applied' => array_filter($request->only(['search', 'region', 'country', 'availability'])),
                'results_count' => $mentors->total(),
                'has_search' => $request->filled('search'),
                'has_region_filter' => $request->filled('region'),
                'has_country_filter' => $request->filled('country'),
                'has_availability_filter' => $request->filled('availability')
            ],
            ActivityLog::SEVERITY_INFO
        );

        return Inertia::render('Dashboard/Mentors/Index', [
            'mentors' => $mentors,
            'filters' => $request->only(['search', 'region', 'country', 'availability']),
        ]);
    }

    public function show(MentorProfile $mentor)
    {
        $mentor->load('user');

        // Log mentor profile view
        ActivityLoggerService::log(
            ActivityLog::EVENT_UPDATED,
            'mentors',
            'Viewed mentor profile',
            "User viewed mentor profile: {$mentor->user->name}",
            $mentor,
            [
                'user_id' => request()->user()->id,
                'mentor_profile_id' => $mentor->id,
                'mentor_name' => $mentor->user->name,
                'mentor_domain' => $mentor->domain,
                'mentor_region' => $mentor->region,
                'mentor_country' => $mentor->country,
                'mentor_rating' => $mentor->rating,
                'mentor_availability' => $mentor->availability_status,
                'mentor_completed_sessions' => $mentor->completed_mentorships_count,
                'mentor_slots_left' => $mentor->remainingCapacity(),
                'viewer_id' => request()->user()->id,
                'viewer_name' => request()->user()->name
            ],
            ActivityLog::SEVERITY_INFO
        );

        return Inertia::render('Dashboard/Mentors/Show', [
            'mentor' => [
                'id' => $mentor->id,
                'name' => $mentor->user->name,
                'title' => $mentor->title,
                'domain' => $mentor->domain,
                'region' => $mentor->region,
                'country' => $mentor->country,
                'rating' => $mentor->rating,
                'completed' => $mentor->completed_mentorships_count,
                'bio' => $mentor->bio,
                'expertise_summary' => $mentor->expertise_summary,
                'languages' => $mentor->languages ?? [],
                'skills' => $mentor->skills ?? [],
                'certifications' => $mentor->certifications ?? [],
                'availability_status' => $mentor->availability_status,
                'slots_left' => $mentor->remainingCapacity(),
            ],
        ]);
    }
}