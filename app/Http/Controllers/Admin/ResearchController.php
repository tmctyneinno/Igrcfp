<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    public function index(Request $request)
    {
        $documents = Research::with('admin')
            ->when($request->type, function ($query) use ($request) {
                return $query->where('document_type', $request->type);
            })
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category', $request->category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Preserve filter parameters in pagination links
        $documents->appends($request->only(['type', 'category']));

        return view('admin.research.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.research.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|in:research,whitepaper',
            'category' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'is_published' => 'boolean'
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('research_documents', $fileName, 'public');

        Research::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'document_type' => $request->document_type,
            'category' => $request->category,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'is_published' => $request->boolean('is_published', true),
            'admin_id' => auth()->guard('admin')->id()
        ]);

        return redirect()->route('admin.research.index')
            ->with('success', 'Document uploaded successfully.');
    }

    public function edit(Research $research)
    {
        return view('admin.research.edit', compact('research'));
    }

    public function update(Request $request, Research $research)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|in:research,whitepaper',
            'category' => 'nullable|string|max:100',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'is_published' => 'boolean'
        ]);

        $data = $request->only(['title', 'description', 'document_type', 'category', 'is_published']);
        $data['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('public')->exists($research->file_path)) {
                Storage::disk('public')->delete($research->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('research_documents', $fileName, 'public');

            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getMimeType();
            $data['file_size'] = $file->getSize();
        }

        $research->update($data);

        return redirect()->route('admin.research.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Research $research)
    {
        if (Storage::disk('public')->exists($research->file_path)) {
            Storage::disk('public')->delete($research->file_path);
        }

        $research->delete();

        return redirect()->route('admin.research.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function categories()
    {
        $categories = Research::distinct()->pluck('category')->filter();
        return view('admin.research.categories', compact('categories'));
    }
}