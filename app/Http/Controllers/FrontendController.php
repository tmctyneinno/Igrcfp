<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('frontend.index');
    }

    public function aboutus()
    {
        return view('frontend.aboutus');
    }

    public function igrcfp(){
        return view('frontend.aboutus');
    }

    public function structure(){
        return view('frontend.structure');
    }

    public function membership(){
        return view('frontend.membership');
    }

    
}
