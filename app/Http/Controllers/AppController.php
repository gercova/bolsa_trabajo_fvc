<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\HistoricalReview;
use App\Models\JobOffer;
use App\Models\Partner;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Contracts\View\View;

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
        return view('admission.cepre-fvc');
    }
    
    // examen de admisión
    public function admissionExam(): View {
        return view('admission.admission-exam');
    }

    // becas y créditos
    public function scholarshipsAndCredits(): View {
        return view('admission.scholarships-and-credits');
    }

    // matrículas
    public function enrollments(): View {
        return view('admission.enrollments');
    }

    // programas de estudio
    public function studyPrograms(): View {
        $programs = StudyProgram::where('is_active', true)->with('modules')->get();
        return view('study-programs', compact('programs'));
    }

    public function program(StudyProgram $program): View {
        $program->load(['images', 'modules']);
        return view('study-program', compact('program'));
    }

    // transparencia
    public function documentsManagement(): View {
        return view('transparency.management-documents');
    }
    public function statistics(): View {
        return view('transparency.statistics');
    }
    public function managementReports(): View {
        return view('transparency.investment-and-management');
    }
    public function licensment(): View {
        return view('transparency.licensment');
    }
    public function complaintsBook(): View {
        return view('transparency.complaints-book');
    }

    // Trámites
    public function partsTable(): View {
        return view('procedures.parts-table');
    }

    public function tupa(): View {
        return view('procedures.tupa');
    }

    // quienes somos
    public function whoWeAre(): View {
        $enterprise = Enterprise::get();
        return view('aboutus.who-we-are', compact('enterprise'));
    }

    // historia
    public function history(): View {
        $histories = HistoricalReview::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('aboutus.history', compact('histories'));
    }

    // organigrama institucional
    public function institutionalOrganizationChart(): View {
        return view('aboutus.institutional-organization-chart');
    }

    // plana jerarquica
    public function hierarchicalFlat(): View {
        return view('aboutus.hierarchical-flat');
    }

    // plana de docentes
    public function teachersStaff(): View {
        return view('aboutus.teachers-staff');
    }

    // plana administrativa
    public function administrativeStaff(): View {
        $teachers = User::get();
        return view('aboutus.administrative-staff', compact('teachers'));
    }
    
    // consejo de estudiantes
    public function studentCouncil(): View {
        return view('aboutus.student-council');
    }

    public function offers(): View {
        return view('ofertas.index');
    }
}
