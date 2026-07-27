<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Inertia\Inertia;
use Inertia\Response;

class IslandController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Islands', [
            'provinces' => Province::select('slug', 'name', 'capital_city', 'map_lat', 'map_lng')->get(),
        ]);
    }
}
