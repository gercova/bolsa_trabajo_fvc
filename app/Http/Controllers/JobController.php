<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobOffer;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = JobOffer::where('is_active', true)->orderBy('created_at', 'desc')->get();
        return view('jobs', compact('jobs'));
    }
}