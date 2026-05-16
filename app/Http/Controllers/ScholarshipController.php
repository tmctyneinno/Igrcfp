<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;
use Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ScholarshipController extends Controller
{
    public function store(Request $request)
    {
        // Verify reCAPTCHA
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);
        $recaptchaData = $recaptchaResponse->json();
        if (!$recaptchaData['success']) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'nationality' => 'required|string|max:100',
            'country_of_residence' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'academic_background' => 'nullable|string|max:1000',
            'highest_qualification' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'year_completed' => 'required|string|max:4',
            'current_role' => 'nullable|string|max:255',
            'organisation' => 'nullable|string|max:255',
            'preferred_programmes' => 'required|array|min:1|max:3',
            'personal_statement' => 'required|string|min:100|max:5000',
            'declaration' => 'required|accepted',
            'post_id' => 'nullable|exists:articles,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $application = ScholarshipApplication::create([
            'post_id' => $request->post_id,
            'full_name' => $request->full_name,
            'nationality' => $request->nationality,
            'country_of_residence' => $request->country_of_residence,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'academic_background' => $request->academic_background,
            'highest_qualification' => $request->highest_qualification,
            'institution' => $request->institution,
            'year_completed' => $request->year_completed,
            'current_role' => $request->current_role,
            'organisation' => $request->organisation,
            'preferred_programmes' => $request->preferred_programmes,
            'personal_statement' => $request->personal_statement,
            'declaration' => true,
            'status' => 'pending',
        ]);

       // Send email to IGRCFP scholarships team
        Mail::send([], [], function ($message) use ($application) {
            $message->to('scholarships@igrcfp.org')
                ->subject('New Scholarship Application: ' . $application->full_name)
                ->replyTo($application->email, $application->full_name)
                ->html($this->buildAdminEmailHTML($application));
        });

        // Send confirmation email to applicant
        Mail::send([], [], function ($message) use ($application) {
            $message->to($application->email, $application->full_name)
                ->subject('Scholarship Application Received - IGRCFP')
                ->html($this->buildApplicantEmailHTML($application));
        });

        return back()->with('success', 'Application submitted successfully! Check your email for confirmation.');
    }

    private function buildAdminEmailHTML($application)
{
    $programmes = implode('</li><li>', $application->preferred_programmes);
    
    return <<<HTML
    <!DOCTYPE html>
    <html>
    <head><meta charset="utf-8"></head>
    <body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <div style="background: #1e3a8a; padding: 20px; text-align: center;">
            <h2 style="color: white; margin: 0;">New Scholarship Application</h2>
        </div>
        
        <div style="padding: 20px; border: 1px solid #e5e7eb;">
            <h3 style="color: #1e3a8a;">APPLICANT DETAILS</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Name:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">{$application->full_name}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Email:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">{$application->email}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Phone:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">{$application->phone_number}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Nationality:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">{$application->nationality}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Country:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">{$application->country_of_residence}</td></tr>
            </table>
            
            <h3 style="color: #1e3a8a; margin-top: 20px;">ACADEMIC BACKGROUND</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Qualification:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">{$application->highest_qualification}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Institution:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">{$application->institution}</td></tr>
                <tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Year:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">{$application->year_completed}</td></tr>
            </table>
            
            <h3 style="color: #1e3a8a; margin-top: 20px;">SELECTED PROGRAMMES</h3>
            <ul style="background: #f3f4f6; padding: 15px 30px; border-radius: 8px;">
                <li>{$programmes}</li>
            </ul>
            
            <h3 style="color: #1e3a8a; margin-top: 20px;">PERSONAL STATEMENT</h3>
            <div style="background: #f3f4f6; padding: 15px; border-radius: 8px;">
                <p>{$application->personal_statement}</p>
            </div>
        </div>
        
        <div style="background: #f3f4f6; padding: 15px; text-align: center; font-size: 12px; color: #6b7280;">
            <p>Submitted: {$application->created_at->format('F d, Y H:i')} | Status: Pending Review</p>
            <p>IGRCFP - www.igrcfp.org</p>
        </div>
    </body>
    </html>
    HTML;
}

private function buildApplicantEmailHTML($application)
{
    $programmes = implode(', ', $application->preferred_programmes);
    
    return <<<HTML
    <!DOCTYPE html>
    <html>
    <head><meta charset="utf-8"></head>
    <body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <div style="background: #1e3a8a; padding: 20px; text-align: center;">
            <h2 style="color: white; margin: 0;">Application Received</h2>
        </div>
        
        <div style="padding: 20px; border: 1px solid #e5e7eb;">
            <p>Dear <strong>{$application->full_name}</strong>,</p>
            
            <p>Thank you for applying to the <strong>IGRCFP Emerging Professionals Scholarship Programme 2026</strong>.</p>
            
            <div style="background: #f0fdf4; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <h3 style="color: #166534; margin: 0 0 10px 0;">✅ Application Summary</h3>
                <table style="width: 100%;">
                    <tr><td><strong>Name:</strong></td><td>{$application->full_name}</td></tr>
                    <tr><td><strong>Programmes:</strong></td><td>{$programmes}</td></tr>
                </table>
            </div>
            
            <h3 style="color: #1e3a8a;">What Happens Next:</h3>
            <ol>
                <li>Your application will be reviewed by our scholarship committee</li>
                <li>Shortlisted candidates will be contacted for further assessment</li>
                <li>Final decisions will be communicated via email within 4-6 weeks after the deadline (June 30, 2026)</li>
            </ol>
            
            <p>If you have any questions, please contact us at <a href="mailto:scholarships@igrcfp.org">scholarships@igrcfp.org</a>.</p>
            
            <p>Best regards,<br><strong>IGRCFP Scholarship Committee</strong><br>www.igrcfp.org</p>
        </div>
    </body>
    </html>
    HTML;
}

}