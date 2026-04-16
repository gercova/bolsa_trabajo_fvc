<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller {

    public function __construct(){

    }

    public function index(Request $request): View {
        $search = $request->input('search');

        $users = User::when($search, function ($query) use ($search) {
            $query->where('names', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
                // Descomenta la siguiente línea si agregas 'telefono' a tu BD
                // ->orWhere('telefono', 'LIKE', "%{$search}%")
        })->paginate(10);

        return view('admin.user.index', compact('users', 'search'));
    }

    public function updatePassword(Request $request): JsonResponse {

    }

    public function update(){

    }
}
