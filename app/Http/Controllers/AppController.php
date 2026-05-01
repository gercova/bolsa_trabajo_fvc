<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AppController extends Controller {

    public function index(): View {
        return view('home');
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
}
