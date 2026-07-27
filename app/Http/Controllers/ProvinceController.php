<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Inertia\Inertia;
use Inertia\Response;

class ProvinceController extends Controller
{
    public function show(Province $province): Response
    {
        $province->load('cultureItems');

        $orderedSlugs = Province::orderBy('name')->pluck('slug');

        return Inertia::render('ProvinceShow', [
            'province' => $province,
            'cultureItemsByCategory' => $province->cultureItems->groupBy('category'),
            'collectionIndex' => $orderedSlugs->search($province->slug) + 1,
            'collectionTotal' => $orderedSlugs->count(),
        ]);
    }
}
