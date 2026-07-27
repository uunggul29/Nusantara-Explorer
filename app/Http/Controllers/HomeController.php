<?php

namespace App\Http\Controllers;

use App\Models\HomeStat;
use App\Models\TeamMember;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'teamMembers' => TeamMember::orderBy('sort_order')->get(),
            'homeStats' => HomeStat::orderBy('sort_order')->get(),
        ]);
    }
}
