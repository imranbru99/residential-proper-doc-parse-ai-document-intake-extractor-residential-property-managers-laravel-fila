<?php

namespace App\Http\Controllers;

use App\Support\ModuleManager;
use Illuminate\View\View;

class PublicSiteController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'modules' => ModuleManager::all(),
        ]);
    }

    public function features(): View
    {
        return view('public.features', [
            'modules' => ModuleManager::all(),
        ]);
    }

    public function workflow(): View
    {
        return view('public.workflow');
    }

    public function pricing(): View
    {
        return view('public.pricing');
    }

    public function demo(): View
    {
        return view('public.demo');
    }
}
