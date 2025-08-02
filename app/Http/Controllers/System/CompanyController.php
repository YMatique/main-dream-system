<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    //
    /**
     * Display the company management page.
     */
    public function index()
    {
        return view('livewire.system.company-management');
    }

    /**
     * Show company details.
     */
    public function show(int $id)
    {
        return view('livewire.system.company-details', compact('id'));
    }
}
