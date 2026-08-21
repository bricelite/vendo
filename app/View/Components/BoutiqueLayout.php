<?php

namespace App\View\Components;

use App\Models\Boutique;
use Illuminate\View\Component;
use Illuminate\View\View;

class BoutiqueLayout extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public Boutique $boutique,
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.boutique');
    }
}
