<?php

namespace App\Http\Controllers;

use App\Models\Aac;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AacController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aac = Aac::with('pengguna')
            ->orderByRaw('CASE WHEN urutan IS NULL THEN 1 ELSE 0 END')
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if (request()->expectsJson()) {
            return response()->json($aac);
        }

        return view('dashboard.aac.aac', compact('aac'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.aac.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'gambar_path' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'urutan' => 'nullable|integer|min:1',
            'status_aktif' => 'boolean',
        ], [
            'judul.required' => 'Judul/ungkapan wajib diisi',
            'gambar_path.image' => 'Gambar AAC harus berupa file gambar.',
            'gambar_path.mimes' => 'Format gambar AAC harus JPG, JPEG, PNG, WEBP, atau SVG.',
            'gambar_path.max' => 'Ukuran gambar AAC terlalu besar. Maksimal 5 MB. Silakan kompres atau pilih gambar yang lebih kecil.',
        ]);

        if ($request->hasFile('gambar_path')) {
            $file = $request->file('gambar_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('aac/gambar', $fileName, 'public');
            $validated['gambar_path'] = $filePath;
        }

        $validated['dibuat_oleh'] = Auth::id();
        $validated['status_aktif'] = $request->has('status_aktif');

        $aac = Aac::create($validated);

        if ($request->expectsJson()) {
            return response()->json($aac->load('pengguna'), 201);
        }

        return redirect()->route('aac.index')
            ->with('success', 'AAC berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aac = Aac::with('pengguna')->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($aac);
        }

        return view('dashboard.aac.show', compact('aac'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $aac = Aac::findOrFail($id);
        return view('dashboard.aac.edit', compact('aac'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $aac = Aac::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'gambar_path' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'urutan' => 'nullable|integer|min:1',
            'status_aktif' => 'boolean',
        ], [
            'judul.required' => 'Judul/ungkapan wajib diisi',
            'gambar_path.image' => 'Gambar AAC harus berupa file gambar.',
            'gambar_path.mimes' => 'Format gambar AAC harus JPG, JPEG, PNG, WEBP, atau SVG.',
            'gambar_path.max' => 'Ukuran gambar AAC terlalu besar. Maksimal 5 MB. Silakan kompres atau pilih gambar yang lebih kecil.',
        ]);

        if ($request->hasFile('gambar_path')) {
            if ($aac->gambar_path && Storage::disk('public')->exists($aac->gambar_path)) {
                Storage::disk('public')->delete($aac->gambar_path);
            }
            $file = $request->file('gambar_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('aac/gambar', $fileName, 'public');
            $validated['gambar_path'] = $filePath;
        } else {
            $validated['gambar_path'] = $aac->gambar_path;
        }

        $validated['status_aktif'] = $request->has('status_aktif');

        $aac->update($validated);

        if ($request->expectsJson()) {
            return response()->json($aac->refresh()->load('pengguna'));
        }

        return redirect()->route('aac.index')
            ->with('success', 'AAC berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aac = Aac::findOrFail($id);

        if ($aac->gambar_path && Storage::disk('public')->exists($aac->gambar_path)) {
            Storage::disk('public')->delete($aac->gambar_path);
        }

        $aac->delete();

        if (request()->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('aac.index')
            ->with('success', 'AAC berhasil dihapus!');
    }
}
