<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
{
    // Get all published documents
    public function index(Request $request)
    {
        $query = Research::where('is_published', true)
            ->orderBy('created_at', 'desc');

        // Optional filters
        if ($request->has('type')) {
            $query->where('document_type', $request->type);
        }
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $documents = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $documents->through(function ($doc) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'slug' => $doc->slug,
                    'description' => $doc->description,
                    'type' => $doc->document_type,
                    'category' => $doc->category,
                    'file_url' => Storage::url($doc->file_path),
                    'file_name' => $doc->file_name,
                    'file_size' => $doc->file_size,
                    'created_at' => $doc->created_at->format('d M Y'),
                ];
            })
        ]);
    }

    // Get single document details
    public function show($slug)
    {
        $document = Research::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $document->id,
                'title' => $document->title,
                'slug' => $document->slug,
                'description' => $document->description,
                'type' => $document->document_type,
                'category' => $document->category,
                'file_url' => Storage::url($document->file_path),
                'file_name' => $document->file_name,
                'file_size' => $document->file_size,
                'created_at' => $document->created_at->format('d M Y'),
            ]
        ]);
    }
}