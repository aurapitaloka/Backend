<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materi;
use App\Models\Pengguna;
use App\Models\Level;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materi = Materi::with(['pengguna', 'level', 'mataPelajaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($materi);
        }

        return view('dashboard.materi.materi', compact('materi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $levels = Level::where('status_aktif', true)->orderBy('nama')->get();
        $mataPelajarans = MataPelajaran::where('status_aktif', true)->orderBy('nama')->get();
        return view('dashboard.materi.create', compact('levels', 'mataPelajarans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'level_id' => 'nullable|exists:level,id',
            'tipe_konten' => 'required|in:teks,file',
            'konten_teks' => 'nullable|string|required_if:tipe_konten,teks',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240|required_if:tipe_konten,file',
            'cover_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'status_aktif' => 'boolean',
        ], [
            'judul.required' => 'Judul wajib diisi',
            'tipe_konten.required' => 'Tipe konten wajib dipilih',
            'konten_teks.required_if' => 'Konten teks wajib diisi jika tipe konten adalah teks',
            'file_path.required_if' => 'File wajib diupload jika tipe konten adalah file',
            'mata_pelajaran_id.exists' => 'Mata pelajaran yang dipilih tidak valid',
            'level_id.exists' => 'Level yang dipilih tidak valid',
            'cover_path.image' => 'Cover harus berupa gambar',
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materi', $fileName, 'public');
            $validated['file_path'] = $filePath;
        }

        if ($request->hasFile('cover_path')) {
            $cover = $request->file('cover_path');
            $coverName = time() . '_cover_' . $cover->getClientOriginalName();
            $coverPath = $cover->storeAs('materi/covers', $coverName, 'public');
            $validated['cover_path'] = $coverPath;
        }

        $validated['dibuat_oleh'] = Auth::id();
        $validated['status_aktif'] = $request->has('status_aktif') ? true : false;

        Materi::create($validated);
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Materi berhasil ditambahkan!'], 201);
        }

        return redirect()->route('materi.index')
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $materi = Materi::with(['pengguna', 'level', 'mataPelajaran'])->findOrFail($id);
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($materi);
        }

        return view('dashboard.materi.show', compact('materi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $materi = Materi::findOrFail($id);
        $levels = Level::where('status_aktif', true)->orderBy('nama')->get();
        $mataPelajarans = MataPelajaran::where('status_aktif', true)->orderBy('nama')->get();
        return view('dashboard.materi.edit', compact('materi', 'levels', 'mataPelajarans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $materi = Materi::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'level_id' => 'nullable|exists:level,id',
            'tipe_konten' => 'required|in:teks,file',
            'konten_teks' => 'nullable|string|required_if:tipe_konten,teks',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'cover_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'status_aktif' => 'boolean',
        ], [
            'judul.required' => 'Judul wajib diisi',
            'tipe_konten.required' => 'Tipe konten wajib dipilih',
            'konten_teks.required_if' => 'Konten teks wajib diisi jika tipe konten adalah teks',
            'mata_pelajaran_id.exists' => 'Mata pelajaran yang dipilih tidak valid',
            'level_id.exists' => 'Level yang dipilih tidak valid',
            'cover_path.image' => 'Cover harus berupa gambar',
        ]);

        // Handle file upload if new file is provided
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materi', $fileName, 'public');
            $validated['file_path'] = $filePath;
        } else {
            // Keep existing file if not uploading new one
            $validated['file_path'] = $materi->file_path;
        }

        if ($request->hasFile('cover_path')) {
            if ($materi->cover_path && Storage::disk('public')->exists($materi->cover_path)) {
                Storage::disk('public')->delete($materi->cover_path);
            }
            $cover = $request->file('cover_path');
            $coverName = time() . '_cover_' . $cover->getClientOriginalName();
            $coverPath = $cover->storeAs('materi/covers', $coverName, 'public');
            $validated['cover_path'] = $coverPath;
        } else {
            $validated['cover_path'] = $materi->cover_path;
        }

        $validated['status_aktif'] = $request->has('status_aktif') ? true : false;

        $materi->update($validated);

        $materi = $materi->fresh();
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Materi berhasil diperbarui!', 'data' => $materi]);
        }

        return redirect()->route('materi.index')
            ->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $materi = Materi::findOrFail($id);

        // Delete file if exists
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }
        if ($materi->cover_path && Storage::disk('public')->exists($materi->cover_path)) {
            Storage::disk('public')->delete($materi->cover_path);
        }

        $materi->delete();
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(['message' => 'Materi berhasil dihapus!']);
        }

        return redirect()->route('materi.index')
            ->with('success', 'Materi berhasil dihapus!');
    }
}
