@extends('admin.layouts.app')

@section('title', 'Add New Chapter')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Create New Chapter</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.chapters.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            {{-- Region --}}
                            <div class="col-md-6">
                                <label class="form-label">Region Name <span class="text-danger">*</span></label>
                                <input type="text" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region') }}" required>
                                @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="col-md-6">
                                <label class="form-label">Slug (auto-generated if empty)</label>
                                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="e.g. west-africa">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Countries Focus --}}
                            <div class="col-12">
                                <label class="form-label">Countries in Focus</label>
                                <input type="text" name="country_focus" class="form-control @error('country_focus') is-invalid @enderror" value="{{ old('country_focus') }}" placeholder="e.g. Nigeria, Ghana, Senegal">
                                @error('country_focus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Annual Fee --}}
                            <div class="col-md-4">
                                <label class="form-label">Annual Fee (£) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="annual_fee" class="form-control @error('annual_fee') is-invalid @enderror" value="{{ old('annual_fee', 0) }}" required>
                                @error('annual_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Contact Email --}}
                            <div class="col-md-4">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email') }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Meeting Frequency --}}
                            <div class="col-md-4">
                                <label class="form-label">Meeting Frequency <span class="text-danger">*</span></label>
                                <input type="text" name="meeting_frequency" class="form-control @error('meeting_frequency') is-invalid @enderror" value="{{ old('meeting_frequency', 'Quarterly') }}" required>
                                @error('meeting_frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Benefits --}}
                            <div class="col-12">
                                <label class="form-label">Member Benefits (one per line)</label>
                                <textarea name="benefits" rows="5" class="form-control @error('benefits') is-invalid @enderror" placeholder="Enter each benefit on a new line">{{ old('benefits') }}</textarea>
                                <small class="text-muted">Will be saved as a list automatically.</small>
                                @error('benefits')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Active Status --}}
                            <div class="col-md-6">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Set as Active
                                    </label>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">Save Chapter</button>
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