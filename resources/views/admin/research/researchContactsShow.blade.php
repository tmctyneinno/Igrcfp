@extends('admin.layouts.app')

@section('title', 'Submission Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Submission Details</h4>
                    <a href="{{ route('admin.research.research-contacts.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Full Name</label>
                                <p class="form-control-plaintext fs-5">{{ $contact->full_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Job Title</label>
                                <p class="form-control-plaintext fs-5">{{ $contact->title }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Organisation</label>
                                <p class="form-control-plaintext fs-5">{{ $contact->organisation }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Email Address</label>
                                <p class="form-control-plaintext fs-5">
                                    <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                        {{ $contact->email }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Document ID</label>
                                <p class="form-control-plaintext fs-5">{{ $contact->document_id }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Submitted At</label>
                                <p class="form-control-plaintext fs-5">
                                    {{ $contact->created_at->format('d M Y H:i:s') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Document Title</label>
                                <p class="form-control-plaintext fs-5 border p-3 bg-light rounded">
                                    {{ $contact->document_title }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection