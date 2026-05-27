@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Contact Message #{{ $contactMessage->id }}</h6>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">Back to Contacts</a>
    </div>

    <div class="row gy-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Message</h6>
                    <span class="badge bg-{{
                        $contactMessage->status === 'new' ? 'warning' :
                        ($contactMessage->status === 'in_progress' ? 'info' :
                        ($contactMessage->status === 'resolved' ? 'success' : 'danger'))
                    }}">
                        {{ ucfirst(str_replace('_', ' ', $contactMessage->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="mb-1">{{ $contactMessage->full_name }}</h5>
                        <div class="text-muted">{{ $contactMessage->email }}</div>
                        <div class="text-muted">{{ $contactMessage->formatted_phone ?? 'No phone number provided' }}</div>
                    </div>

                    <div class="p-3 rounded-3 bg-light">
                        {!! nl2br(e($contactMessage->message)) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Submission Details</h6>
                </div>
                <div class="card-body d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Submitted:</span>
                        <span class="fw-medium">{{ $contactMessage->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Privacy Agreed:</span>
                        <span class="fw-medium">{{ $contactMessage->privacy_agreed ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Country Code:</span>
                        <span class="fw-medium">{{ $contactMessage->country_code }}</span>
                    </div>
                    <div>
                        <div class="text-muted mb-1">IP Address</div>
                        <div class="fw-medium">{{ $contactMessage->ip_address ?? 'Not recorded' }}</div>
                    </div>
                    <div>
                        <div class="text-muted mb-1">User Agent</div>
                        <div class="fw-medium" style="word-break: break-word;">
                            {{ $contactMessage->user_agent ?? 'Not recorded' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
