<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeStat;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class HomeStatController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/HomeStats/Index', [
            'homeStats' => HomeStat::orderBy('sort_order')->get(),
        ]);
    }

    public function edit(HomeStat $homeStat): Response
    {
        return Inertia::render('Admin/HomeStats/Form', ['homeStat' => $homeStat]);
    }

    public function update(Request $request, HomeStat $homeStat): RedirectResponse
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $homeStat->update($data);

        return redirect()->route('admin.home-stats.index')->with('success', 'Statistik berhasil diperbarui.');
    }
}
