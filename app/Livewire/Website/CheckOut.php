<?php

namespace App\Livewire\Website;

use Livewire\Component;

class CheckOut extends Component
{
    public function render()
    {
        return view('livewire.website.check-out')->layout('layouts.website');
    }
}
