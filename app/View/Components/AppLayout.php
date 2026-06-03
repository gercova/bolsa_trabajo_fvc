<?php

namespace App\View\Components;

use App\Models\Enterprise;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View {
        $enterprise = Enterprise::first();
        return view('layouts.app', compact('enterprise'));
    }
}
