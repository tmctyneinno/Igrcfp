<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
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
            'title' => 'Certifications & Trainings ',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function eventsIndex(){
        $currentDate = now()->toDateString();
        
        $events = Event::query()
            ->where('status', 'published')
            ->select('*')
            ->selectRaw("CASE WHEN start_date >= '{$currentDate}' THEN 0 ELSE 1 END as is_upcoming")
            ->orderBy('is_upcoming')
            ->orderByRaw("CASE WHEN start_date >= '{$currentDate}' THEN start_date ELSE start_date DESC END")
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

    public function blog(){
        return Inertia::render('Blog/Index', [
            'title' => 'Blog',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function contact(){
        return Inertia::render('Contact/Index', [
            'title' => 'Contact Us',
            'description' => 'Our friendly team would love to hear from you.',
        ]);
    }

    public function trainingCalendar(){
        return Inertia::render('Certifications/TrainingCalendar', [
            'title' => 'Training Calendar',
            'description' => 'Our friendly team would love to hear from you.',
        ]);
    }
   
}
