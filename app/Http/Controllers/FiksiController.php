<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fiksi;
use Illuminate\Support\Facades\Storage;

class FiksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $fiksi = Fiksi::with('pengguna')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('id', 'like', "%{$search}%")
                        ->orWhere('judul_buku', 'like', "%{$search}%")
                        ->orWhere('penulis', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('tahun_terbit', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('pengguna', function ($penggunaQuery) use ($search) {
                            $penggunaQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        if (request()->expectsJson()) {
            return response()->json($fiksi);
        }

        return view('dashboard.fiksi.fiksi', compact('fiksi', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.fiksi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_buku' => 'required|string|max:200',
            'penulis' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'deskripsi' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'status_aktif' => 'boolean',
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi',
            'penulis.required' => 'Penulis wajib diisi',
            'file_path.file' => 'File buku tidak valid. Pilih file PDF, DOC, atau DOCX.',
            'file_path.mimes' => 'Format file buku harus PDF, DOC, atau DOCX.',
            'file_path.max' => 'Ukuran file buku terlalu besar. Maksimal 10 MB. Silakan kompres atau pilih file yang lebih kecil.',
            'jumlah_halaman.integer' => 'Jumlah halaman harus berupa angka.',
            'jumlah_halaman.min' => 'Jumlah halaman minimal 1.',
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('fiksi', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        $validated['dibuat_oleh'] = Auth::id();
        $validated['status_aktif'] = $request->has('status_aktif') ? true : false;

        $fiksi = Fiksi::create($validated);

        if ($request->expectsJson()) {
            return response()->json($fiksi->load('pengguna'), 201);
        }

        return redirect()->route('fiksi.index')
            ->with('success', 'Fiksi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fiksi = Fiksi::with('pengguna')->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($fiksi);
        }

        return view('dashboard.fiksi.show', compact('fiksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $fiksi = Fiksi::findOrFail($id);
        return view('dashboard.fiksi.edit', compact('fiksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $fiksi = Fiksi::findOrFail($id);

        $validated = $request->validate([
            'judul_buku' => 'required|string|max:200',
            'penulis' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'deskripsi' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'status_aktif' => 'boolean',
        ], [
            'judul_buku.required' => 'Judul buku wajib diisi',
            'penulis.required' => 'Penulis wajib diisi',
            'file_path.file' => 'File buku tidak valid. Pilih file PDF, DOC, atau DOCX.',
            'file_path.mimes' => 'Format file buku harus PDF, DOC, atau DOCX.',
            'file_path.max' => 'Ukuran file buku terlalu besar. Maksimal 10 MB. Silakan kompres atau pilih file yang lebih kecil.',
            'jumlah_halaman.integer' => 'Jumlah halaman harus berupa angka.',
            'jumlah_halaman.min' => 'Jumlah halaman minimal 1.',
        ]);

        // Handle file upload if new file is provided
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($fiksi->file_path && Storage::disk('public')->exists($fiksi->file_path)) {
                Storage::disk('public')->delete($fiksi->file_path);
            }

            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('fiksi', $fileName, 'public');
            $validated['file_path'] = $filePath;
        } else {
            // Keep existing file if not uploading new one
            $validated['file_path'] = $fiksi->file_path;
        }

        $validated['status_aktif'] = $request->has('status_aktif') ? true : false;

        $fiksi->update($validated);

        if ($request->expectsJson()) {
            return response()->json($fiksi->refresh()->load('pengguna'));
        }

        return redirect()->route('fiksi.index')
            ->with('success', 'Fiksi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fiksi = Fiksi::findOrFail($id);

        // Delete file if exists
        if ($fiksi->file_path && Storage::disk('public')->exists($fiksi->file_path)) {
            Storage::disk('public')->delete($fiksi->file_path);
        }

        $fiksi->delete();

        if (request()->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('fiksi.index')
            ->with('success', 'Fiksi berhasil dihapus!');
    }
}
