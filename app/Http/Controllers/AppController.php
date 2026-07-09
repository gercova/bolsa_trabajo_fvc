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
        $programs   = StudyProgram::where('is_active', true)->get();
        $partners   = Partner::where('is_active', true)->get();
        $jobOffers  = JobOffer::where('is_active', true)->get();
        $users      = User::where('is_active', true)->get();
        return view('home', compact('partners', 'jobOffers', 'users', 'programs'));
    }

    // cepre fvc
    public function ceprefvc(): View {
        return view('cepre-fvc');
    }
    
    // examen de admisión
    public function admissionExam(): View {
        return view('admission-exam');
    }

    // becas y créditos
    public function scholarshipsAndCredits(): View {
        return view('scholarships-and-credits');
    }

    public function enrollments(): View {
        return view('enrollments');
    }

    public function studyPrograms(): View {
        $programs = StudyProgram::get();
        return view('study-programs', compact('programs'));
    }

    public function program(StudyProgram $program): View {
        return view('study-program', compact('program'));
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
        return view('teachers-staff');
    }

    // plana administrativa
    public function administrativeStaff(): View {
        $teachers = User::get();
        return view('administrative-staff', compact('teachers'));
    }
    
    // consejo de estudiantes
    public function studentCouncil(): View {
        return view('student-council');
    }


}
