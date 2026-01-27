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
        $courses = Course::published() // Using the published scope from your model
            ->withCount('modules')
            ->orderBy('is_featured', 'desc')
            ->orderBy('is_popular', 'desc')
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'short_description' => $course->short_description,
                    'banner_image' => $course->banner_image, 
                    'image_url' => $course->image_url, 
                    'level' => $course->level,
                    'duration' => $course->duration,
                    'price' => $course->price,
                    'discount_price' => $course->discount_price,
                    'modules_count' => $course->modules_count,
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
            ->selectRaw("CASE WHEN start_date >= '{$currentDate}' THEN start_date ELSE DATE('9999-12-31') - start_date END as sort_date")
            ->orderBy('is_upcoming')
            ->orderBy('sort_date')
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

        $upcomingEvents = Event::where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        // If we don't have enough upcoming events, get events from same venue
        if ($upcomingEvents->count() < 3 && $event->venue) {
            $venueEvents = Event::where('status', 'published')
                ->where('id', '!=', $event->id)
                ->where('venue', $event->venue)
                ->whereNotIn('id', $upcomingEvents->pluck('id'))
                ->inRandomOrder()
                ->limit(3 - $upcomingEvents->count())
                ->get();
            
            $upcomingEvents = $upcomingEvents->merge($venueEvents);
        }

        // If still not enough, get events from same location
        if ($upcomingEvents->count() < 3 && $event->location) {
            $locationEvents = Event::where('status', 'published')
                ->where('id', '!=', $event->id)
                ->where('location', $event->location)
                ->whereNotIn('id', $upcomingEvents->pluck('id'))
                ->inRandomOrder()
                ->limit(3 - $upcomingEvents->count())
                ->get();
            
            $upcomingEvents = $upcomingEvents->merge($locationEvents);
        }

        // Final fallback: random events
        if ($upcomingEvents->count() < 3) {
            $randomEvents = Event::where('status', 'published')
                ->where('id', '!=', $event->id)
                ->whereNotIn('id', $upcomingEvents->pluck('id'))
                ->inRandomOrder()
                ->limit(3 - $upcomingEvents->count())
                ->get();
            
            $upcomingEvents = $upcomingEvents->merge($randomEvents);
        }

        return Inertia::render('Events/Show', [
            'event' => $event,
            'relatedEvents' => $upcomingEvents,
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

    public function news()
    {
        return Inertia::render('News/Index', [
            'title' => 'News',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }

    public function privacyPolicy(){
        return Inertia::render('PrivacyPolicy/Index', [
            'title' => 'News',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]);
    }
   
}
