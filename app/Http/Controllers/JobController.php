<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobOffer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(): View {
        $jobs = JobOffer::where('is_active', true)->orderBy('created_at', 'desc')->get();
        return view('admin.jobs.index', compact('jobs'));
    }

    public function store(): JsonResponse {

    }

    public function update(): JsonResponse {

    }

    public function destroy(): JsonResponse {

    }
}