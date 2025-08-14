<?php

namespace App\Livewire\Website;

use Livewire\Component;

class Project extends Component
{
    public function render()
    {
        return view('livewire.website.project')->layout('layouts.website');
    }
}
