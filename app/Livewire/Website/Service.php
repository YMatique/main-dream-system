<?php

namespace App\Livewire\Website;

use Livewire\Component;

class Service extends Component
{
     public $selectedService = 'engineering'; // Serviço padrão

    public function selectService($service)
    {
        $this->selectedService = $service;
    }
    public function render()
    {
        return view('livewire.website.service')->layout('layouts.website')->title(__('messages.services.title') . ' - MainGDream');
    }
}
