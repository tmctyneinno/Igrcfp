<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ProgrammesController extends Controller
{
    public function index()
    {
        return Inertia::render('Programmes/Index');
    } 

    public function grc()
    {
        return Inertia::render('Programmes/GRC');
    }

    public function financialCrime()
    {
        return Inertia::render('Programmes/FinancialCrime');
    }

    public function crypto()
    {
        return Inertia::render('Programmes/Crypto');
    }

    public function cybersecurity()
    {
        return Inertia::render('Programmes/Cybersecurity');
    }

    public function ai() 
    {
        return Inertia::render('Programmes/AI');
    }

    public function allCourses()
    {
        return Inertia::render('Programmes/AllCourses');
    }
}