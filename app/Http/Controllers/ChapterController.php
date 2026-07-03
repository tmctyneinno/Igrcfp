<?php
namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Event;
use App\Models\ChapterLeadership;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChapterController extends Controller
{
    /**
     * Display all chapters
     */
    public function index()
{
    $chapters = Chapter::orderBy('region', 'asc')
        ->get()
        ->map(function ($chapter) {
            $chapter->members_count = 0;
            $chapter->events_count = $chapter->events()->where('status', 'published')->count();
            return $chapter;
        });

    return Inertia::render('Chapters/Index', compact('chapters'));
}

    
    /**
     * Display single chapter details
     */
    /**
 * Display single chapter details
 */
public function show($slug)
{
    $chapter = Chapter::where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    // ✅ Only count events — skip members count for now
    $chapter->loadCount([
        'events' => function ($query) {
            $query->where('status', 'published');
        }
    ]);

    // ✅ Manually set members_count to 0 to avoid errors
    $chapter->members_count = 0;

    $upcomingEvents = Event::where('chapter_id', $chapter->id)
        ->where('status', 'published')
        ->where('start_date', '>=', now())
        ->orderBy('start_date', 'asc')
        ->take(5)
        ->get([
            'title', 'slug', 'short_description', 'start_date',
            'start_time', 'location', 'image', 'capacity', 'available_seats'
        ]);

    $leadership = ChapterLeadership::where('chapter_id', $chapter->id)
        ->orderBy('created_at', 'asc')
        ->get(['name', 'role']);

    $memberBenefits = $chapter->benefits ? 
        (is_array($chapter->benefits) ? $chapter->benefits : json_decode($chapter->benefits, true) ?? []) 
        : [];

    return Inertia::render('Chapters/Show', [
        'chapter' => $chapter,
        'upcomingEvents' => $upcomingEvents,
        'leadership' => $leadership,
        'memberBenefits' => $memberBenefits
    ]);
}

    /**
     * Optional: Admin method to create a chapter
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region' => 'required|string|max:100|unique:chapters',
            'slug' => 'required|string|max:100|unique:chapters',
            'country_focus' => 'nullable|string',
            'description' => 'required|string',
            'annual_fee' => 'required|numeric|min:0',
            'contact_email' => 'nullable|email',
            'meeting_frequency' => 'required|string',
            'benefits' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        Chapter::create($validated);

        return redirect()->route('chapters.index')->with('success', 'Chapter created successfully.');
    }

    /**
     * Optional: Admin method to update a chapter
     */
    public function update(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);

        $validated = $request->validate([
            'region' => 'required|string|max:100|unique:chapters,region,' . $id,
            'slug' => 'required|string|max:100|unique:chapters,slug,' . $id,
            'country_focus' => 'nullable|string',
            'description' => 'required|string',
            'annual_fee' => 'required|numeric|min:0',
            'contact_email' => 'nullable|email',
            'meeting_frequency' => 'required|string',
            'benefits' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $chapter->update($validated);

        return redirect()->route('chapters.index')->with('success', 'Chapter updated successfully.');
    }
}