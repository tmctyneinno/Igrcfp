@extends('admin.layouts.app')

@section('title', "Edit Chapter: {$chapter->region}")

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Chapter: {{ $chapter->region }}</h5>
                    <a href="{{ route('admin.chapters.index') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-left-line me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.chapters.update', $chapter) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Region --}}
                            <div class="col-md-6">
                                <label class="form-label">Region Name <span class="text-danger">*</span></label>
                                <input type="text" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region', $chapter->region) }}" required>
                                @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="col-md-6">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $chapter->slug) }}">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Countries Focus --}}
                            <div class="col-12">
                                <label class="form-label">Countries in Focus</label>
                                <input type="text" name="country_focus" class="form-control @error('country_focus') is-invalid @enderror" value="{{ old('country_focus', $chapter->country_focus) }}">
                                @error('country_focus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $chapter->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Annual Fee --}}
                            <div class="col-md-4">
                                <label class="form-label">Annual Fee (£) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="annual_fee" class="form-control @error('annual_fee') is-invalid @enderror" value="{{ old('annual_fee', $chapter->annual_fee) }}" required>
                                @error('annual_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Contact Email --}}
                            <div class="col-md-4">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $chapter->contact_email) }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Meeting Frequency --}}
                            <div class="col-md-4">
                                <label class="form-label">Meeting Frequency <span class="text-danger">*</span></label>
                                <input type="text" name="meeting_frequency" class="form-control @error('meeting_frequency') is-invalid @enderror" value="{{ old('meeting_frequency', $chapter->meeting_frequency) }}" required>
                                @error('meeting_frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Benefits --}}
                            <div class="col-12">
                                <label class="form-label">Member Benefits (one per line)</label>
                                @php
                                    $benefitsText = is_array($chapter->benefits) ? implode("\n", $chapter->benefits) : $chapter->benefits;
                                @endphp
                                <textarea name="benefits" rows="5" class="form-control @error('benefits') is-invalid @enderror" placeholder="Enter each benefit on a new line">{{ old('benefits', $benefitsText) }}</textarea>
                                <small class="text-muted">Saved as a list.</small>
                                @error('benefits')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Active Status --}}
                            <div class="col-md-6">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $chapter->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Set as Active
                                    </label>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">Update Chapter</button>
                                <a href="{{ route('admin.chapters.index') }}" class="btn btn-light ms-2">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection