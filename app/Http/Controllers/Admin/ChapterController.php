<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterLeadership;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    // List all chapters
    public function index()
    {
        // $chapters = Chapter::orderBy('region', 'asc')->paginate(10);
        $chapters = Chapter::withCount('events')->latest()->paginate(10);
        return view('admin.chapters.index', compact('chapters'));
    }

    // Create form
    public function create()
    {
        return view('admin.chapters.create');
    }

    // Save new chapter
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region'            => 'required|string|max:100|unique:chapters',
            'slug'              => 'nullable|string|max:100|unique:chapters',
            'country_focus'     => 'nullable|string|max:255',
            'description'       => 'required|string',
            'annual_fee'        => 'required|numeric|min:0',
            'contact_email'     => 'nullable|email|max:150',
            'meeting_frequency' => 'required|string|max:100',
            'benefits'          => 'nullable|string',
            'is_active'         => 'boolean'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['region']);
        }

        if (!empty($validated['benefits'])) {
            $validated['benefits'] = array_filter(array_map('trim', explode("\n", $validated['benefits'])));
        } else {
            $validated['benefits'] = null;
        }

        Chapter::create($validated);
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter created.');
    }

    // Show single chapter
    public function show(Chapter $chapter)
    {
        return view('admin.chapters.show', compact('chapter'));
    }

    // Edit form
    public function edit(Chapter $chapter)
    {
        return view('admin.chapters.edit', compact('chapter'));
    }

    // Update chapter
    public function update(Request $request, Chapter $chapter)
    {
        $validated = $request->validate([
            'region'            => 'required|string|max:100|unique:chapters,region,' . $chapter->id,
            'slug'              => 'nullable|string|max:100|unique:chapters,slug,' . $chapter->id,
            'country_focus'     => 'nullable|string|max:255',
            'description'       => 'required|string',
            'annual_fee'        => 'required|numeric|min:0',
            'contact_email'     => 'nullable|email|max:150',
            'meeting_frequency' => 'required|string|max:100',
            'benefits'          => 'nullable|string',
            'is_active'         => 'boolean'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['region']);
        }

        if (!empty($validated['benefits'])) {
            $validated['benefits'] = array_filter(array_map('trim', explode("\n", $validated['benefits'])));
        } else {
            $validated['benefits'] = null;
        }

        $chapter->update($validated);
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter updated.');
    }

    // Delete chapter
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return redirect()->route('admin.chapters.index')->with('success', 'Chapter deleted.');
    }

    // ------------------------------
    // LEADERSHIP METHODS
    // ------------------------------
    public function leadership(Chapter $chapter)
    {
        $leaders = ChapterLeadership::where('chapter_id', $chapter->id)->get();
        return view('admin.chapters.leadership', compact('chapter', 'leaders'));
    }

    public function storeLeadership(Request $request, Chapter $chapter)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'role'  => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:50',
        ]);

        $chapter->leadership()->create($validated);
        return redirect()->back()->with('success', 'Leader added successfully.');
    }

    public function destroyLeadership(Chapter $chapter, ChapterLeadership $leader)
    {
        $leader->delete();
        return redirect()->back()->with('success', 'Leader removed.');
    }

    // ------------------------------
    // EVENTS METHOD
    // ------------------------------
    public function events(Chapter $chapter)
    {
        $events = \App\Models\Event::where('chapter_id', $chapter->id)
            ->orderBy('start_date', 'asc')
            ->select('id', 'title', 'start_date', 'start_time', 'status') // only existing columns
            ->paginate(10);

        return view('admin.chapters.events', compact('chapter', 'events'));
    }
}