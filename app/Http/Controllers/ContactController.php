<?php
// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormConfirmation;
use App\Mail\ContactFormSubmitted;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function create(Request $request): View
    {
        return view('contact.create', [
            'title' => 'Contact Us',
            'countryCodes' => $this->getCountryCodes(),
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function store(ContactFormRequest $request): RedirectResponse
    {
        try {
            // Create contact message
            $contactMessage = ContactMessage::create([
                'first_name' => $request->validated('first_name'),
                'last_name' => $request->validated('last_name'),
                'email' => $request->validated('email'),
                'phone' => $request->validated('phone'),
                'country_code' => $request->validated('country_code', 'NG'),
                'message' => $request->validated('message'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'privacy_agreed' => $request->validated('agree', false),
            ]);

            // Send notification to admin
            Mail::to(config('mail.admin.address', config('mail.from.address')))
                ->send(new ContactFormSubmitted($contactMessage));

            // Send confirmation to user
            Mail::to($contactMessage->email)
                ->send(new ContactFormConfirmation($contactMessage));

            // Log successful submission
            Log::info('Contact form submitted successfully', [
                'id' => $contactMessage->id,
                'email' => $contactMessage->email,
                'ip' => $request->ip(),
            ]);

            return redirect()
                ->route('contact.create')
                ->with('success', 'Thank you for your message! We\'ll get back to you within 24-48 hours.');

        } catch (\Exception $e) {
            // Log error but don't show technical details to user
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $request->except(['_token', 'agree']),
                'ip' => $request->ip(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again or contact us directly.');
        }
    }

    /**
     * Get country codes for dropdown.
     */
    private function getCountryCodes(): array
    {
        return [
            ['code' => 'NG', 'name' => 'Nigeria (+234)'],
            ['code' => 'GH', 'name' => 'Ghana (+233)'],
            ['code' => 'KE', 'name' => 'Kenya (+254)'],
            ['code' => 'ZA', 'name' => 'South Africa (+27)'],
            ['code' => 'US', 'name' => 'USA (+1)'],
            ['code' => 'GB', 'name' => 'UK (+44)'],
            ['code' => 'CA', 'name' => 'Canada (+1)'],
            ['code' => 'AU', 'name' => 'Australia (+61)'],
            ['code' => 'FR', 'name' => 'France (+33)'],
            ['code' => 'DE', 'name' => 'Germany (+49)'],
        ];
    }
}