<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CultureItem;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CultureItemController extends Controller
{
    public function create(Province $province): Response
    {
        return Inertia::render('Admin/CultureItems/Form', [
            'province' => $province,
            'cultureItem' => null,
        ]);
    }

    public function store(Request $request, Province $province): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = $request->file('image')->store("provinces/{$province->slug}", 'public');
        $data['sort_order'] = $province->cultureItems()->where('category', $data['category'])->count();

        $province->cultureItems()->create($data);

        return redirect()->route('admin.provinces.edit', $province)->with('success', 'Item budaya berhasil ditambahkan.');
    }

    public function edit(CultureItem $cultureItem): Response
    {
        return Inertia::render('Admin/CultureItems/Form', [
            'province' => $cultureItem->province,
            'cultureItem' => $cultureItem,
        ]);
    }

    public function update(Request $request, CultureItem $cultureItem): RedirectResponse
    {
        $data = $this->validated($request, forUpdate: true);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store("provinces/{$cultureItem->province->slug}", 'public');
        }

        $cultureItem->update($data);

        return redirect()->route('admin.provinces.edit', $cultureItem->province)->with('success', 'Item budaya berhasil diperbarui.');
    }

    public function destroy(CultureItem $cultureItem): RedirectResponse
    {
        $province = $cultureItem->province;
        $cultureItem->delete();

        return redirect()->route('admin.provinces.edit', $province)->with('success', 'Item budaya berhasil dihapus.');
    }

    private function validated(Request $request, bool $forUpdate = false): array
    {
        return $request->validate([
            'category' => 'required|in:artifact,food,tradition,arts',
            'title' => 'required|string|max:255',
            'image' => ($forUpdate ? 'nullable' : 'required').'|image|max:10240',
        ]);
    }
}
