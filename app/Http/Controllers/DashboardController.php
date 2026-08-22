<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Blog;
use App\Models\Claim;
use App\Models\JobOffer;
use App\Models\Partner;
use App\Models\StudyProgram;
use App\Models\Tupa;
use App\Models\User;
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

        // ── Admisiones ────────────────────────────────────────
        $admissionsTotal  = Admission::count();
        $admissionsActive = Admission::where('is_active', true)->count();

        // ── TUPA ──────────────────────────────────────────────
        $tupaTotal  = Tupa::count();
        $tupaActive = Tupa::where('is_active', true)->count();

        // ── Blog ──────────────────────────────────────────────
        $blogsTotal     = Blog::count();
        $blogsPublished = Blog::where('is_published', true)->count();

        return view('dashboard', compact(
            'usersTotal', 'usersActive', 'usersThisMonth', 'usersRoles', 'recentUsers',
            'jobOffersTotal', 'jobOffersActive', 'recentOffers',
            'claimsTotal', 'claimsThisMonth', 'recentClaims',
            'partnersTotal', 'partnersActive',
            'programsTotal', 'programsActive',
            'admissionsTotal', 'admissionsActive',
            'tupaTotal', 'tupaActive',
            'blogsTotal', 'blogsPublished',
        ));
    }
}
