<?php

namespace App\Http\Controllers;

use App\Models\MentorProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;


class MentorController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = MentorProfile::query()
    //         ->with('user')
    //         ->where('is_active', true);

    //     if ($request->filled('search')) {
    //         $search = $request->string('search');
    //         $query->where(function ($q) use ($search) {
    //             $q->whereHas('user', function ($userQuery) use ($search) {
    //                 $userQuery->where('name', 'like', "%{$search}%");
    //             })->orWhere('title', 'like', "%{$search}%")
    //               ->orWhere('domain', 'like', "%{$search}%");
    //         });
    //     }

    //     if ($request->filled('region')) {
    //         $query->where('region', $request->string('region'));
    //     }

    //     if ($request->filled('country')) {
    //         $query->where('country', $request->string('country'));
    //     }

    //     if ($request->filled('availability')) {
    //         $query->where('availability_status', $request->string('availability'));
    //     }

    //     $mentors = $query->orderBy('rating', 'desc')->paginate(12)->withQueryString();

    //     return view('mentors.index', [
    //         'mentors' => $mentors,
    //         'filters' => $request->only(['search', 'region', 'country', 'availability']),
    //     ]);
    // }















    public function index(Request $request)
    {
        $query = MentorProfile::query()
            ->with('user')
            ->where('is_active', true);

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

        if ($request->filled('region')) {
            $query->where('region', $request->string('region'));
        }

        if ($request->filled('country')) {
            $query->where('country', $request->string('country'));
        }

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

        return Inertia::render('Dashboard/Mentors/Index', [
            'mentors' => $mentors,
            'filters' => $request->only(['search', 'region', 'country', 'availability']),
        ]);
    }
















    // public function show(MentorProfile $mentor)
    // {
    //     $mentor->load('user');

    //     return view('mentors.show', [
    //         'mentor' => $mentor,
    //     ]);
    // }



    public function show(MentorProfile $mentor)
    {
        $mentor->load('user');

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
