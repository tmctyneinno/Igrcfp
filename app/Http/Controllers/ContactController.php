<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ContactFormConfirmation;
use App\Mail\ContactFormSubmitted;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Rules\Recaptcha;
use App\Services\ActivityLoggerService;
use App\Services\BrevoMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function __construct(
        protected BrevoMailService $brevoMailService
    ) {}
    
    public function store(Request $request)
    {
        
        Log::info('IP Address:', [$request->ip()]);
        Log::info('User Agent:', [$request->userAgent()]);
        
        try {
            // Manual validation
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:50', 'min:2'],
                'last_name' => ['required', 'string', 'max:50', 'min:2'],
                'email' => ['required', 'email:rfc,dns', 'max:100'],
                'country_code' => ['required', 'string', 'max:5'],
                'phone' => ['required', 'string', 'max:20'], // Removed regex temporarily
                'message' => ['required', 'string', 'min:10', 'max:2000'],
                'agree' => ['required', 'accepted'],
                'g-recaptcha-response' => ['required', new Recaptcha],
            ], [
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
            ]);
            
            Log::info('Validation passed:', $validated);
            
            // Clean phone number
            $cleanPhone = preg_replace('/[^\d+]/', '', $validated['phone']);
            Log::info('Phone cleaned:', ['original' => $validated['phone'], 'cleaned' => $cleanPhone]);
            
              // Map camelCase to snake_case for database
            $contactMessage = ContactMessage::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => preg_replace('/[^\d+]/', '', $validated['phone']),
                'country_code' => $validated['country_code'],
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'privacy_agreed' => $request->boolean('agree'),
            ]);

            ActivityLoggerService::created($contactMessage, 'contacts');

            Log::info('Contact created successfully:', [
                'id' => $contactMessage->id,
                'email' => $contactMessage->email,
                'created_at' => $contactMessage->created_at,
            ]);
            
            // Try to send emails (optional - comment out if causing issues)
            try {
                $adminEmail = "enquiries@igrcfp.org";
                Log::info('Attempting to send emails:', [
                    'admin_email' => $adminEmail,
                    'user_email' => $contactMessage->email,
                ]); 
                 
                $adminMail = $this->brevoMailService->sendContactFormSubmitted(
                    $adminEmail,
                    new ContactFormSubmitted($contactMessage)
                );
                $userMail = $this->brevoMailService->sendContactFormConfirmation(
                    $contactMessage->email,
                    new ContactFormConfirmation($contactMessage)
                );
                
                Log::info('Emails sent successfully');
                ActivityLoggerService::log(
                    ActivityLog::EVENT_CREATED,
                    'contacts',
                    'Contact emails sent via Brevo',
                    "Contact submission emails sent for {$contactMessage->email}",
                    $contactMessage,
                    [
                        'provider' => 'brevo',
                        'admin_email' => $adminEmail,
                        'user_email' => $contactMessage->email,
                        'admin_message_id' => $adminMail['message_id'] ?? null,
                        'user_message_id' => $userMail['message_id'] ?? null,
                    ],
                    ActivityLog::SEVERITY_INFO
                );
            } catch (\Exception $mailException) {
                Log::warning('Email sending failed (but form was saved):', [
                    'error' => $mailException->getMessage(),
                    'contact_id' => $contactMessage->id,
                ]);
                ActivityLoggerService::log(
                    ActivityLog::EVENT_CREATED,
                    'contacts',
                    'Contact email delivery failed',
                    "Brevo failed to send contact emails for {$contactMessage->email}",
                    $contactMessage,
                    [
                        'provider' => 'brevo',
                        'admin_email' => $adminEmail ?? 'enquiries@igrcfp.org',
                        'user_email' => $contactMessage->email,
                        'error' => $mailException->getMessage(),
                    ],
                    ActivityLog::SEVERITY_ERROR
                );
                // Continue even if email fails
            }

            Log::info('====== CONTACT FORM SUBMISSION END ======');
            
            // For Inertia, we need to return a proper response
            if ($request->inertia()) {
                return redirect()
                    ->route('contact')
                    ->with('success', 'Thank you for your message! We\'ll get back to you within 24-48 hours.');
            }
            
            return response()->json([
                'message' => 'Message sent successfully!',
                'success' => true,
                'contact_id' => $contactMessage->id,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            
            // For Inertia, re-throw the validation exception
            throw $e;
            
        } catch (\Exception $e) {
            Log::error('Contact form submission failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            
            if ($request->inertia()) {
                return back()
                    ->withInput()
                    ->with('error', 'Something went wrong. Please try again or contact us directly.');
            }
            
            return response()->json([
                'message' => 'Something went wrong. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
