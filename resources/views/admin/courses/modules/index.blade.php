@extends('admin.layouts.app')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Course Modules: {{ $course->title }}</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.index') }}" class="hover-text-primary">Courses</a>
            </li>
            <li>-</li>
            <li class="fw-medium">
                <a href="{{ route('admin.courses.show', $course) }}" class="hover-text-primary">{{ Str::limit($course->title, 30) }}</a>
            </li>
            <li>-</li>
            <li class="fw-medium">Modules</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0">Modules ({{ $modules->count() }})</h6>
            <a href="{{ route('admin.courses.modules.create', $course) }}" class="btn btn-primary btn-sm">
                <iconify-icon icon="ic:baseline-plus" class="icon"></iconify-icon>
                Add Module
            </a>
        </div>
        <div class="card-body p-24">
            @if($modules->count() > 0)
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Module</th>
                                <th>Title</th>
                                <th>Code</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modules as $module)
                            <tr>
                                <td>{{ $module->module_number }}</td>
                               <td>
                                    <span class="fw-medium">Module {{ $module->module_number }}</span>
                                    @php
                                        $quizCount = $module->quizzes ? $module->quizzes->count() : 0;
                                    @endphp
                                    @if($quizCount > 0)
                                        <br>
                                        <a href="{{ route('admin.assessments.index', ['module_id' => $module->id]) }}" class="text-sm text-primary-600 hover-underline">
                                            📝 {{ $quizCount }} Quiz{{ $quizCount != 1 ? 'zes' : '' }}
                                        </a>
                                    @else
                                        <br>
                                        <span class="text-sm text-muted">No quizzes</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">{{ $module->title }}</span>
                                        <p class="text-sm text-secondary-light mb-0">{!! Str::limit($module->course_outline, 50) !!}</p>
                                    </div>
                                </td>
                                <td>{{ $module->code ?? '—' }}</td>
                                <td>{{ $module->estimated_hours }} hr{{ $module->estimated_hours != 1 ? 's' : '' }}</td>
                                <td>
                                    @if($module->is_active)
                                        <span class="badge bg-success-600 text-white px-12 py-6 radius-8">Active</span>
                                    @else
                                        <span class="badge bg-warning-600 text-white px-12 py-6 radius-8">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-2 justify-content-center">
                                        <button type="button" 
                                                class="bg-info-focus bg-hover-info-200 text-info-600 w-35-px h-35-px d-inline-flex justify-content-center align-items-center rounded-circle border-0"
                                                title="Quick View" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewModuleModal{{ $module->id }}">
                                            <iconify-icon icon="majesticons:eye-line"></iconify-icon>
                                        </button>
                                        <a href="{{ route('admin.courses.modules.edit', ['course' => $course, 'module' => $module]) }}" 
                                           class="bg-success-focus bg-hover-success-200 text-success-600 w-35-px h-35-px d-inline-flex justify-content-center align-items-center rounded-circle"
                                           title="Edit">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.courses.modules.destroy', ['course' => $course, 'module' => $module]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-danger-focus bg-hover-danger-200 text-danger-600 w-35-px h-35-px d-inline-flex justify-content-center align-items-center rounded-circle border-0"
                                                    onclick="return confirm('Are you sure you want to delete this module?')"
                                                    title="Delete">
                                                <iconify-icon icon="fluent:delete-24-regular"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Quick View Modal -->
                            <div class="modal fade" id="viewModuleModal{{ $module->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Module {{ $module->module_number }}: {{ $module->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <h6 class="fw-semibold">Course Outline</h6>
                                                <p class="text-secondary-light">{{ $module->course_outline }}</p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <h6 class="fw-semibold">Full Content</h6>
                                                <div class="bg-light p-3 rounded-8" style="max-height: 300px; overflow-y: auto;">
                                                    {!! nl2br(e($module->full_content)) !!}
                                                </div>
                                            </div>
                                            
                                            @if($module->learning_objectives)
                                                <div class="mb-3">
                                                    <h6 class="fw-semibold">Learning Objectives</h6>
                                                    <p class="text-secondary-light">{{ $module->learning_objectives }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($module->key_concepts)
                                                <div class="mb-3">
                                                    <h6 class="fw-semibold">Key Concepts</h6>
                                                    <p class="text-secondary-light">{{ $module->key_concepts }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($module->topics_covered)
                                                <div class="mb-3">
                                                    <h6 class="fw-semibold">Topics Covered</h6>
                                                    <p class="text-secondary-light">{{ $module->topics_covered }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h6 class="fw-semibold">Estimated Hours</h6>
                                                    <p class="text-secondary-light">{{ $module->estimated_hours }} hours</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6 class="fw-semibold">Status</h6>
                                                    <p class="text-secondary-light">{{ $module->is_active ? 'Active' : 'Inactive' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6 class="fw-semibold">Code</h6>
                                                    <p class="text-secondary-light">{{ $module->code ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                            <a href="{{ route('admin.courses.modules.edit', ['course' => $course, 'module' => $module]) }}" class="btn btn-primary">
                                                <iconify-icon icon="lucide:edit" class="icon"></iconify-icon>
                                                Edit Module
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <iconify-icon icon="solar:documents-outline" class="icon-4x text-muted mb-3"></iconify-icon>
                    <h6 class="text-muted mb-2">No modules found</h6>
                    <p class="text-muted mb-4">This course doesn't have any modules yet.</p>
                    <a href="{{ route('admin.courses.modules.create', $course) }}" class="btn btn-primary">
                        <iconify-icon icon="ic:baseline-plus" class="icon"></iconify-icon>
                        Add First Module
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .w-35-px { width: 35px; }
    .h-35-px { height: 35px; }
    .rounded-8 { border-radius: 8px; }
    .icon-4x { font-size: 4rem; }
    .px-12 { padding-left: 12px; padding-right: 12px; }
    .py-6 { padding-top: 6px; padding-bottom: 6px; }
    .px-16 { padding-left: 16px; padding-right: 16px; }
    .py-16 { padding-top: 16px; padding-bottom: 16px; }
    .p-24 { padding: 24px; }
    .bg-hover-info-200:hover { background-color: #bfdbfe !important; }
    .bg-hover-success-200:hover { background-color: #bbf7d0 !important; }
    .bg-hover-danger-200:hover { background-color: #fecaca !important; }
</style>
@endpush