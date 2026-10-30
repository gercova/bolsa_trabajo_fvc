<?php

namespace App\Http\Middleware;

use App\Models\Enterprise;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'inertia-app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'enterprise' => fn () => Enterprise::first() ?? Enterprise::getDefault(),

            'headerPrograms' => fn () => StudyProgram::where('is_active', true)
                ->orderBy('order')
                ->with('meta')
                ->get(['id', 'name', 'slug']),

            'auth' => [
                'user' => fn () => $request->user() ? [
                    'id' => $request->user()->id,
                    'names' => $request->user()->names,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                ] : null,
            ],

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}
