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

    public function certification(){
        return view('frontend.certification');
    }

    public function event(){
        return view('frontend.event');
    }

    public function cgfcs(){
        return view('frontend.cgfcs');
    }

    public function blog(){
        return view('frontend.blog.index');
    }

    public function blogDetails(){
        return view('frontend.blog.details');
    }

    public function getInvolved(){
        return view('frontend.getInvolved');
    }

    public function contact(){
        return view('frontend.contact');
    }


}