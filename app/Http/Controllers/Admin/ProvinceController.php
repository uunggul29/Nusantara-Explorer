<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProvinceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Provinces/Index', [
            'provinces' => Province::withCount('cultureItems')->orderBy('name')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Provinces/Form', [
            'province' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data = $this->handleUploads($request, $data, $data['slug']);

        Province::create($data);

        return redirect()->route('admin.provinces.index')->with('success', 'Provinsi berhasil ditambahkan.');
    }

    public function edit(Province $province): Response
    {
        $province->load('cultureItems');

        return Inertia::render('Admin/Provinces/Form', [
            'province' => $province,
        ]);
    }

    public function update(Request $request, Province $province): RedirectResponse
    {
        $data = $this->validated($request, $province->id);
        $data = $this->handleUploads($request, $data, $province->slug, $province);

        $province->update($data);

        return redirect()->route('admin.provinces.index')->with('success', 'Provinsi berhasil diperbarui.');
    }

    public function destroy(Province $province): RedirectResponse
    {
        $province->delete();

        return redirect()->route('admin.provinces.index')->with('success', 'Provinsi berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'slug' => 'required|string|alpha_dash|unique:provinces,slug'.($ignoreId ? ",{$ignoreId}" : ''),
            'name' => 'required|string|max:255',
            'display_name_html' => 'nullable|string|max:255',
            'capital_city' => 'required|string|max:255',
            'region_group' => 'nullable|string|max:255',
            'map_lat' => 'required|numeric',
            'map_lng' => 'required|numeric',
            'mini_game_embed_id' => 'required|string|max:64',
            'about_intro_html' => 'required|string',
            'history_article_html' => 'required|string',
            'hero_image' => 'nullable|image|max:10240',
            'history_top_image_1' => 'nullable|image|max:10240',
            'history_top_image_2' => 'nullable|image|max:10240',
            'history_bottom_image' => 'nullable|image|max:10240',
        ]);
    }

    private function handleUploads(Request $request, array $data, string $slug, ?Province $existing = null): array
    {
        $data['hero_image_path'] = $existing?->hero_image_path;
        if ($request->hasFile('hero_image')) {
            $data['hero_image_path'] = $request->file('hero_image')->store("provinces/{$slug}", 'public');
        }

        $topImages = $existing?->history_top_images ?? [];
        foreach ([0 => 'history_top_image_1', 1 => 'history_top_image_2'] as $index => $field) {
            if ($request->hasFile($field)) {
                $topImages[$index] = $request->file($field)->store("provinces/{$slug}", 'public');
            }
        }
        $data['history_top_images'] = array_values(array_filter($topImages));

        $data['history_bottom_image'] = $existing?->history_bottom_image;
        if ($request->hasFile('history_bottom_image')) {
            $data['history_bottom_image'] = $request->file('history_bottom_image')->store("provinces/{$slug}", 'public');
        }

        unset($data['hero_image'], $data['history_top_image_1'], $data['history_top_image_2']);

        return $data;
    }
}
