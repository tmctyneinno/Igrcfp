<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::with('instructor')
            ->where('status', 'published') 
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'short_title' => $course->short_title,
                    'slug' => $course->slug,
                    'description' => $course->description,
                    'excerpt' => $course->excerpt,
                    'level' => $course->level,
                    'level_badge' => $course->level_badge,
                    'price' => $course->current_price,
                    'has_discount' => $course->has_discount,
                    'discount_price' => $course->discount_price,
                    'discount_percentage' => $course->discount_percentage,
                    'image_url' => $course->image ? Storage::url($course->image) : null,
                    'instructor' => $course->instructor
                        ? [
                            'id' => $course->instructor->id,
                            'name' => $course->instructor->name,
                        ]
                        : null,
                    'rating' => round($course->averageRating(), 1),
                    'reviews_count' => $course->reviewsCount(),
                    'enrollments_count' => $course->activeEnrollmentsCount,
                    'duration' => $course->duration,
                    'modules_count' => $course->modules_count,
                    'certification' => $course->certification,
                ];
            });

        return Inertia::render('Welcome', [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'courses' => $courses,
        ]);
    }

    public function welcomeToIGRCFP()
    {
        return Inertia::render('About/WelcomeIGRCFP/Index', [
            'title' => 'Welcome to IGRCFP',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function OurStructure(){
        return Inertia::render('About/OurStructure/Index', [
            'title' => 'Our Structure',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function membership(){
        return Inertia::render('Membership/Index', [
            'title' => 'Membership',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function certifications(){
        return Inertia::render('Certifications/Index', [
            'title' => 'Certifications',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function eventsIndex(){
        $events = Event::query()
        ->where('status', 'published')
        ->orderBy('start_date', 'asc')
        ->paginate(10);

        return Inertia::render('Events/Index', [
            'title' => 'Professional Events & Workshops',
            'description' => 'Join IGRCFP for expert-led workshops, seminars, and networking events designed for governance, risk, compliance, and financial crime prevention professionals.',
            'events' => $events,
        ]);
    }

    public function eventShow($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->with('user')
            ->firstOrFail();

        $relatedEvents = Event::where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where(function($query) use ($event) {
                $query->where('venue', $event->venue)
                    ->orWhere('location', $event->location)
                    ->orWhere('start_date', '>=', now());
            })
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return Inertia::render('Events/Show', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
        ]);
    }

    public function eventRegister($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Check if registration is open
        if ($event->registration_status === 'sold_out') {
            abort(403, 'This event is sold out.');
        }

        return Inertia::render('Events/Register', [
            'event' => $event,
        ]);
    }

    public function storeEventRegistration(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Validate registration
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'additional_attendees' => 'nullable|integer|min:0|max:5',
            'dietary_requirements' => 'nullable|string|max:500',
            'special_requirements' => 'nullable|string|max:500',
            'hear_about_event' => 'nullable|string|max:255',
            'agree_to_terms' => 'required|accepted',
        ]);

        // Check availability
        $totalAttendees = 1 + ($validated['additional_attendees'] ?? 0);
        if ($event->available_seats < $totalAttendees) {
            return response()->json([
                'errors' => ['general' => 'Not enough seats available.']
            ], 422);
        }

        // Create registration
        $registration = Registration::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company' => $validated['company'],
            'position' => $validated['position'],
            'additional_attendees' => $validated['additional_attendees'] ?? 0,
            'dietary_requirements' => $validated['dietary_requirements'],
            'special_requirements' => $validated['special_requirements'],
            'hear_about_event' => $validated['hear_about_event'],
            'registration_number' => 'REG-' . strtoupper(Str::random(8)),
            'status' => 'pending',
        ]);

        // Update available seats
        $event->decrement('available_seats', $totalAttendees);

        // Send confirmation email
        Mail::to($validated['email'])->send(new EventRegistrationConfirmation($registration, $event));

        // Send notification to admin
        Mail::to(config('mail.admin_email'))->send(new NewEventRegistration($registration, $event));

        return response()->json([
            'message' => 'Registration successful!',
            'registration' => $registration
        ]);
    }

     public function blog(){
        return Inertia::render('Blog/Index', [
            'title' => 'Blog',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]); 
    }
}
