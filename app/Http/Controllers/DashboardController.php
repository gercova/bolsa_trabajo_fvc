<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Blog;
use App\Models\Claim;
use App\Models\DegreeRecord;
use App\Models\EnrollmentSchedule;
use App\Models\InstitutionalCarousel;
use App\Models\JobOffer;
use App\Models\ManagementDocument;
use App\Models\Partner;
use App\Models\Scholarship;
use App\Models\StudentRecord;
use App\Models\StudyProgram;
use App\Models\Tupa;
use App\Models\User;
use App\Models\VisitorCounter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        // ── Usuarios ──────────────────────────────────────────
        $usersTotal      = User::count();
        $usersActive     = User::where('is_active', true)->count();
        $usersThisMonth  = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $usersRoles      = User::selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();
        $recentUsers     = User::latest()->take(6)->get();

        // ── Bolsa de Trabajo (JobOffers) ──────────────────────
        $jobOffersTotal  = JobOffer::count();
        $jobOffersActive = JobOffer::where('is_active', true)->count();
        $recentOffers    = JobOffer::latest()->take(5)->get();

        // ── Reclamos ──────────────────────────────────────────
        $claimsTotal     = Claim::count();
        $claimsPending   = Claim::where('status', 'pendiente')->orWhereNull('status')->count();
        $claimsThisMonth = Claim::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $recentClaims    = Claim::latest()->take(5)->get();

        // ── Partners / Aliados ────────────────────────────────
        $partnersTotal   = Partner::count();
        $partnersActive  = Partner::where('is_active', true)->count();

        // ── Programas de Estudio ──────────────────────────────
        $programsTotal   = StudyProgram::count();
        $programsActive  = StudyProgram::where('is_active', true)->count();

        // ── Admisiones & Matrículas ───────────────────────────
        $admissionsTotal  = Admission::count();
        $admissionsActive = Admission::where('is_active', true)->count();
        $recentAdmissions = Admission::latest()->take(4)->get();
        $enrollmentSchedulesActive = EnrollmentSchedule::where('is_active', true)->count();
        $scholarshipsTotal = Scholarship::count();

        // ── TUPA & Documentos ─────────────────────────────────
        $tupaTotal       = Tupa::count();
        $tupaActive      = Tupa::where('is_active', true)->count();
        $documentsTotal  = ManagementDocument::count();
        $documentsActive = ManagementDocument::where('is_active', true)->count();

        // ── Blog / Noticias ───────────────────────────────────
        $blogsTotal      = Blog::count();
        $blogsPublished  = Blog::where('is_published', true)->count();
        $recentBlogs     = Blog::latest()->take(4)->get();

        // ── Carrusel Institucional ────────────────────────────
        $carouselsTotal  = InstitutionalCarousel::count();
        $carouselsActive = InstitutionalCarousel::where('is_active', true)->count();

        // ── Contador de Visitas & Transparencia ───────────────
        $totalVisits     = VisitorCounter::getTotalVisits();
        $visitDigits     = VisitorCounter::getPaddedDigits($totalVisits, 6);
        $degreeRecordsTotal = DegreeRecord::count();
        $studentRecordsTotal = StudentRecord::count();

        return view('dashboard', compact(
            'usersTotal', 'usersActive', 'usersThisMonth', 'usersRoles', 'recentUsers',
            'jobOffersTotal', 'jobOffersActive', 'recentOffers',
            'claimsTotal', 'claimsPending', 'claimsThisMonth', 'recentClaims',
            'partnersTotal', 'partnersActive',
            'programsTotal', 'programsActive',
            'admissionsTotal', 'admissionsActive', 'recentAdmissions',
            'enrollmentSchedulesActive', 'scholarshipsTotal',
            'tupaTotal', 'tupaActive',
            'documentsTotal', 'documentsActive',
            'blogsTotal', 'blogsPublished', 'recentBlogs',
            'carouselsTotal', 'carouselsActive',
            'totalVisits', 'visitDigits',
            'degreeRecordsTotal', 'studentRecordsTotal'
        ));
    }
}
