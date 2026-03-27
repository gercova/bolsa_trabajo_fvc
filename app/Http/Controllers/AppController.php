<?php

namespace App\Http\Controllers;

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
        return view('aboutus');
    }
}
