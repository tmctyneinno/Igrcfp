<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use Inertia\Inertia;
 
class DashboardController extends Controller
{
   
    
    public function index()
    {
        // Pass enrollment redirect to frontend if it exists
        $enrollmentRedirect = session('enrollment_redirect');
        
        if ($enrollmentRedirect) {
            // Clear it from session after passing to frontend
            session()->forget('enrollment_redirect');
            
            return inertia('Dashboard/Index', [
                'enrollmentRedirect' => $enrollmentRedirect,
            ]);
        }
        
        return inertia('Dashboard/Index');
    }

}
