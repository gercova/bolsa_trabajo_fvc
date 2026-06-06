<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Partner;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AppController extends Controller {

    public function index(): View {
        $partners   = Partner::where('is_active', true)->get();
        $jobOffers  = JobOffer::where('is_active', true)->get();
        $users      = User::where('is_active', true)->get();
        return view('home', compact('partners', 'jobOffers', 'users'));
    }

    public function studyPrograms(): View {
        $programs = StudyProgram::get();
        return view('studyprograms', compact('programs'));
    }

    public function offers(): View {
        return view('ofertas.index');
    }

    public function aboutus(): View {
        $director   = User::where('id', 2)->first();
        $jua        = User::where('id', 3)->first();
        $be         = User::where('id', 4)->first();
        $partners   = Partner::where('is_active', true)->get();
        return view('aboutus', compact('partners', 'director', 'jua', 'be'));
    }

    public function institutionalOrganizationChart(): View {
        return view('');
    }

    // plana jerarquica
    public function hierarchicalFlat(): View {
        return view('');
    }

    // plana de docentes
    public function teachersStaff(): View {
        return view('');
    }

    // plana administrativa
    public function administrativeStaff(): View {
        return view('');
    }

    public function studentCouncil(): View {
        
    }

}
