<?php

use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

// Settings Routes (Protected - admin & super_admin)
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'admin.role:admin,super_admin'])->group(function () {
    // General Settings
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');
    
    Route::put('/settings/general', [SettingsController::class, 'updateGeneral'])
        ->name('settings.general.update');
    
    // Website Settings
    Route::put('/settings/website', [SettingsController::class, 'updateWebsite'])
        ->name('settings.website.update');
    
    // Email Settings
    Route::get('/settings/email', [SettingsController::class, 'email'])
        ->name('settings.email.index');
    
    Route::put('/settings/email', [SettingsController::class, 'updateEmail'])
        ->name('settings.email.update');
    
    Route::post('/settings/email/test', [SettingsController::class, 'testEmail'])
        ->name('settings.email.test');
    
    // Payment Settings
    Route::get('/settings/payment', [SettingsController::class, 'payment'])
        ->name('settings.payment.index');
    
    Route::put('/settings/payment', [SettingsController::class, 'updatePayment'])
        ->name('settings.payment.update');
    
    // Social Media Settings
    Route::get('/settings/social', [SettingsController::class, 'social'])
        ->name('settings.social.index');
    
    Route::put('/settings/social', [SettingsController::class, 'updateSocial'])
        ->name('settings.social.update');
    
    // SEO Settings
    Route::get('/settings/seo', [SettingsController::class, 'seo'])
        ->name('settings.seo.index');
    
    Route::put('/settings/seo', [SettingsController::class, 'updateSeo'])
        ->name('settings.seo.update');
    
    // Notification Settings
    Route::get('/settings/notifications', [SettingsController::class, 'notifications'])
        ->name('settings.notifications.index');
    
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])
        ->name('settings.notifications.update');
    
    // Security Settings
    Route::get('/settings/security', [SettingsController::class, 'security'])
        ->name('settings.security.index');
    
    Route::put('/settings/security', [SettingsController::class, 'updateSecurity'])
        ->name('settings.security.update');
    
    // Backup Settings
    Route::get('/settings/backup', [SettingsController::class, 'backup'])
        ->name('settings.backup.index');
    
    Route::post('/settings/backup/create', [SettingsController::class, 'createBackup'])
        ->name('settings.backup.create');
    
    Route::get('/settings/backup/download/{filename}', [SettingsController::class, 'downloadBackup'])
        ->name('settings.backup.download');
    
    Route::delete('/settings/backup/{filename}', [SettingsController::class, 'deleteBackup'])
        ->name('settings.backup.destroy');
    
    // Cache Management
    Route::post('/settings/cache/clear', [SettingsController::class, 'clearCache'])
        ->name('settings.cache.clear');
    
    // System Information
    Route::get('/settings/system', [SettingsController::class, 'system'])
        ->name('settings.system.index');
});