<?php

namespace App\Http\Controllers;

use App\Models\LandingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingItemController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search', ''));

        $landingItems = LandingItem::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('id', 'like', "%{$search}%")
                        ->orWhere('section', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('badge', 'like', "%{$search}%")
                        ->orWhere('button_label', 'like', "%{$search}%")
                        ->orWhere('meta_one', 'like', "%{$search}%")
                        ->orWhere('meta_two', 'like', "%{$search}%");
                });
            })
            ->orderBy('section')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.landing.index', compact('landingItems', 'search'));
    }

    public function create()
    {
        $sections = LandingItem::SECTIONS;
        return view('dashboard.landing.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $sections = array_keys(LandingItem::SECTIONS);

        $validated = $request->validate([
            'section' => 'required|in:' . implode(',', $sections),
            'title' => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:100',
            'button_label' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'meta_one' => 'nullable|string|max:255',
            'meta_two' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'boolean',
        ], [
            'section.required' => 'Bagian landing wajib dipilih',
            'title.required' => 'Judul wajib diisi',
            'image.image' => 'Gambar landing harus berupa file gambar.',
            'image.mimes' => 'Format gambar landing harus JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran gambar landing terlalu besar. Maksimal 5 MB. Silakan kompres atau pilih gambar yang lebih kecil.',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('landing', $fileName, 'public');
            $validated['image_path'] = $filePath;
        }

        $validated['is_active'] = $request->has('is_active');

        LandingItem::create($validated);

        return redirect()->route('landing.index')
            ->with('success', 'Konten landing berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $landingItem = LandingItem::findOrFail($id);
        $sections = LandingItem::SECTIONS;
        return view('dashboard.landing.show', compact('landingItem', 'sections'));
    }

    public function edit(string $id)
    {
        $landingItem = LandingItem::findOrFail($id);
        $sections = LandingItem::SECTIONS;
        return view('dashboard.landing.edit', compact('landingItem', 'sections'));
    }

    public function update(Request $request, string $id)
    {
        $landingItem = LandingItem::findOrFail($id);
        $sections = array_keys(LandingItem::SECTIONS);

        $validated = $request->validate([
            'section' => 'required|in:' . implode(',', $sections),
            'title' => 'required|string|max:200',
            'subtitle' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:100',
            'button_label' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'meta_one' => 'nullable|string|max:255',
            'meta_two' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'boolean',
        ], [
            'section.required' => 'Bagian landing wajib dipilih',
            'title.required' => 'Judul wajib diisi',
            'image.image' => 'Gambar landing harus berupa file gambar.',
            'image.mimes' => 'Format gambar landing harus JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran gambar landing terlalu besar. Maksimal 5 MB. Silakan kompres atau pilih gambar yang lebih kecil.',
        ]);

        if ($request->hasFile('image')) {
            if ($landingItem->image_path && Storage::disk('public')->exists($landingItem->image_path)) {
                Storage::disk('public')->delete($landingItem->image_path);
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('landing', $fileName, 'public');
            $validated['image_path'] = $filePath;
        } else {
            $validated['image_path'] = $landingItem->image_path;
        }

        $validated['is_active'] = $request->has('is_active');

        $landingItem->update($validated);

        return redirect()->route('landing.index')
            ->with('success', 'Konten landing berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $landingItem = LandingItem::findOrFail($id);

        if ($landingItem->image_path && Storage::disk('public')->exists($landingItem->image_path)) {
            Storage::disk('public')->delete($landingItem->image_path);
        }

        $landingItem->delete();

        return redirect()->route('landing.index')
            ->with('success', 'Konten landing berhasil dihapus!');
    }
}
