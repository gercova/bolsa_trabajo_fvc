<?php

namespace App\Http\Controllers;

use App\Models\HistoricalReview;
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
        $programs = StudyProgram::where('is_active', true)->with('modules')->get();
        return view('study-programs', compact('programs'));
    }

    public function program(StudyProgram $program): View {
        $program->load(['images', 'modules']);
        return view('study-program', compact('program'));
    }

    // transparencia
    public function documentsManagement(): View {
        return view('transparencia.documentos-de-gestion');
    }
    public function statistics(): View {
        return view('transparencia.estadisticas');
    }
    public function managementReports(): View {
        return view('transparencia.inversion-y-gestion');
    }
    public function licensment(): View {
        return view('transparencia.licenciamiento');
    }
    public function complaintsBook(): View {
        return view('transparencia.libro-de-reclamaciones');
    }

    // Trámites
    public function partsTable(): View {
        return view('tramites.mesa-de-partes');
    }
    public function tupa(): View {
        return view('tramites.tupa');
    }

    public function aboutus(): View {
        return view('aboutus');
    }

    // historia
    public function history(): View {
        $histories = HistoricalReview::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('aboutus.history', compact('histories'));
    }

    // organigrama institucional
    public function institutionalOrganizationChart(): View {
        return view('nosotros.organigrama-institucional');
    }

    // plana jerarquica
    public function hierarchicalFlat(): View {
        return view('hierarchical-flat');
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

    public function offers(): View {
        return view('ofertas.index');
    }
}
