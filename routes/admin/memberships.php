<?php

use App\Http\Controllers\Admin\MembershipTierController;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\Admin\MembershipApprovalController;
use App\Http\Controllers\Admin\MentorController;
use App\Http\Controllers\Admin\MentorApplicationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth.admin'])->group(function () {
    // Memberships
    Route::resource('membership-tiers', MembershipTierController::class);
    Route::resource('membership-plans', MembershipPlanController::class);

    Route::get('membership-approvals', [MembershipApprovalController::class, 'index'])
        ->name('membership-approvals.index');
    Route::post('membership-approvals/{membership}/approve', [MembershipApprovalController::class, 'approve'])
        ->name('membership-approvals.approve');
    Route::post('membership-approvals/{membership}/decline', [MembershipApprovalController::class, 'decline'])
        ->name('membership-approvals.decline');

    // Mentors
    Route::get('mentors', [MentorController::class, 'index'])->name('mentors.index');
    Route::get('mentors/create', [MentorController::class, 'create'])->name('mentors.create');
    Route::post('mentors', [MentorController::class, 'store'])->name('mentors.store');
    Route::get('mentors/{mentor}/edit', [MentorController::class, 'edit'])->name('mentors.edit');
    Route::put('mentors/{mentor}', [MentorController::class, 'update'])->name('mentors.update');
    Route::post('mentors/{mentor}/toggle', [MentorController::class, 'toggle'])->name('mentors.toggle');

    // Mentor Applications
    Route::get('mentor-applications', [MentorApplicationController::class, 'index'])->name('mentor-applications.index');
    Route::post('mentor-applications/{application}/approve', [MentorApplicationController::class, 'approve'])->name('mentor-applications.approve');
    Route::post('mentor-applications/{application}/decline', [MentorApplicationController::class, 'decline'])->name('mentor-applications.decline');
});
