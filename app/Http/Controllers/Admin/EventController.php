<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $perPage = $request->get('per_page', 10);

        $events = Event::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage);
       
        return Inertia::render('Admin/Events/Index', [  // Changed to 'Index' (capital I)
            'title' => 'Event Management',  // This will be used by AdminLayout
            'filters' => [
                'search' => $search,
                'status' => $status,
                'per_page' => $perPage,
            ],
            'events' => $events,  // Pass the actual events data
        ]);
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:events,title',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'venue' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,cancelled',
            'is_featured' => 'boolean',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'address' => $request->address,
            'venue' => $request->venue,
            'capacity' => $request->capacity,
            'available_seats' => $request->capacity,
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'user_id' => auth()->guard('admin')->id(),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:events,title,' . $event->id,
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'venue' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:' . $event->capacity - $event->available_seats,
            'status' => 'required|in:draft,published,cancelled',
            'is_featured' => 'boolean',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'address' => $request->address,
            'venue' => $request->venue,
            'capacity' => $request->capacity,
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        // Update available seats if capacity changed
        if ($request->capacity != $event->capacity) {
            $data['available_seats'] = $event->available_seats + ($request->capacity - $event->capacity);
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Delete image if exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function toggleStatus(Event $event)
    {
        $statuses = ['draft', 'published', 'cancelled'];
        $currentIndex = array_search($event->status, $statuses);
        $nextStatus = $statuses[($currentIndex + 1) % count($statuses)];

        $event->update(['status' => $nextStatus]);

        return back()->with('success', "Event status changed to {$nextStatus} successfully.");
    }

    public function toggleFeatured(Event $event)
    {
        $event->update(['is_featured' => !$event->is_featured]);

        $status = $event->is_featured ? 'featured' : 'unfeatured';

        return back()->with('success', "Event {$status} successfully.");
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $eventIds = $request->event_ids;

        if (!$eventIds) {
            return back()->with('error', 'Please select at least one event.');
        }

        switch ($action) {
            case 'publish':
                Event::whereIn('id', $eventIds)->update(['status' => 'published']);
                $message = 'Selected events published successfully.';
                break;
            case 'draft':
                Event::whereIn('id', $eventIds)->update(['status' => 'draft']);
                $message = 'Selected events moved to draft successfully.';
                break;
            case 'cancel':
                Event::whereIn('id', $eventIds)->update(['status' => 'cancelled']);
                $message = 'Selected events cancelled successfully.';
                break;
            case 'delete':
                $events = Event::whereIn('id', $eventIds)->get();
                foreach ($events as $event) {
                    if ($event->image) {
                        Storage::disk('public')->delete($event->image);
                    }
                    $event->delete();
                }
                $message = 'Selected events deleted successfully.';
                break;
            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $message);
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
        try {
            // Create registration
            $registration = EventRegistration::create([
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
            // Mail::to($validated['email'])->send(new EventRegistrationConfirmation($registration, $event));

            // Send notification to admin
            // Mail::to(config('mail.admin_email'))->send(new NewEventRegistration($registration, $event));

                return response()->json([
                    'message' => 'Registration successful!',
                    'registration' => $registration
                ], 201);
            } catch (\Exception $e) {
                \Log::error('Registration error: ' . $e->getMessage());
                
                return response()->json([
                    'errors' => ['general' => 'An error occurred during registration. Please try again.']
                ], 500);
            }
        
        }

     public function blog(){
        return Inertia::render('Blog/Index', [
            'title' => 'Blog',
            'description' => 'Learn about the  Institute of Governance, Risk & Compliance & Financial Crime Prevention (IGRCFP)  Professionals body.',
        ]); 
    }
}