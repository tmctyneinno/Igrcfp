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
   
    
    public function index(Request $request)
    {
        if ($request->session()->has('enrollment_redirect')) {
            $redirect = $request->session()->pull('enrollment_redirect');
            return redirect($redirect);
        }
        
        return Inertia::render('Dashboard/Index');
    }

}
