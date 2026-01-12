<?php

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
     * Handle contact form submission.
     */
    public function store(ContactFormRequest $request)
    {
        \Log::info('====== CONTACT FORM SUBMISSION START ======');
        \Log::info('Request data:', $request->all());
        \Log::info('Headers:', ['X-Requested-With' => $request->header('X-Requested-With')]);
        
        try {
            // Validate and get validated data
            $validated = $request->validated();
            \Log::info('Validated data:', $validated);
            
            // Create contact message
            $contactMessage = ContactMessage::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'country_code' => $validated['country_code'],
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'privacy_agreed' => $request->boolean('agree'),
            ]);

            \Log::info('Contact created:', ['id' => $contactMessage->id]);
            
            // Try to send emails
            try {
                $adminEmail = config('mail.from.address');
                \Log::info('Sending emails:', [
                    'admin_email' => $adminEmail,
                    'user_email' => $contactMessage->email,
                ]);
                
                Mail::to($adminEmail)->send(new ContactFormSubmitted($contactMessage));
                Mail::to($contactMessage->email)->send(new ContactFormConfirmation($contactMessage));
                
                \Log::info('Emails sent successfully');
            } catch (\Exception $mailException) {
                \Log::warning('Email sending failed:', [
                    'error' => $mailException->getMessage(),
                    'contact_id' => $contactMessage->id,
                ]);
                // Continue even if email fails
            }

            \Log::info('====== CONTACT FORM SUBMISSION END ======');
            
            return redirect()
                ->route('contact')
                ->with('success', 'Thank you for your message! We\'ll get back to you within 24-48 hours.');

        } catch (\Exception $e) {
            \Log::error('Contact form submission failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again or contact us directly.');
        }
    }

}