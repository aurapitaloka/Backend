<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materi;
use App\Models\Pengguna;
use App\Models\Level;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = trim((string) request('search', ''));
        $user = Auth::user();

        $materi = Materi::with(['pengguna', 'level', 'mataPelajaran'])
            ->when($this->isSiswaApiRequest(), function ($query) use ($user) {
                $user->loadMissing('siswa');

                $query->where('status_aktif', true)
                    ->when($user->siswa?->level_id, function ($levelQuery) use ($user) {
                        $levelQuery->where('level_id', $user->siswa->level_id);
                    });
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhere('tipe_konten', 'like', "%{$search}%")
                        ->when(ctype_digit($search), function ($idQuery) use ($search) {
                            $idQuery->orWhere('id', (int) $search);
                        })
                        ->orWhereHas('mataPelajaran', function ($relation) use ($search) {
                            $relation->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('level', function ($relation) use ($search) {
                            $relation->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('pengguna', function ($relation) use ($search) {
                            $relation->where('nama', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($materi);
        }

        return view('dashboard.materi.materi', compact('materi', 'search'));
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
            'file_path.file' => 'File materi tidak valid. Pilih file PDF, DOC, atau DOCX.',
            'file_path.mimes' => 'Format file materi harus PDF, DOC, atau DOCX.',
            'file_path.max' => 'Ukuran file materi terlalu besar. Maksimal 10 MB. Silakan kompres atau pilih file yang lebih kecil.',
            'mata_pelajaran_id.exists' => 'Mata pelajaran yang dipilih tidak valid',
            'level_id.exists' => 'Level yang dipilih tidak valid',
            'cover_path.image' => 'Cover buku harus berupa gambar.',
            'cover_path.mimes' => 'Format cover buku harus JPG, JPEG, PNG, atau WEBP.',
            'cover_path.max' => 'Ukuran cover buku terlalu besar. Maksimal 5 MB. Silakan kompres atau pilih gambar yang lebih kecil.',
            'jumlah_halaman.integer' => 'Jumlah halaman harus berupa angka.',
            'jumlah_halaman.min' => 'Jumlah halaman minimal 1.',
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

        if ($this->isSiswaApiRequest()) {
            $user = Auth::user();
            $user->loadMissing('siswa');

            if (!$materi->status_aktif) {
                return response()->json(['message' => 'Materi tidak tersedia'], 404);
            }

            if ($user->siswa?->level_id && (int) $materi->level_id !== (int) $user->siswa->level_id) {
                return response()->json(['message' => 'Materi ini bukan untuk kelas kamu.'], 403);
            }
        }

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
            'file_path.file' => 'File materi tidak valid. Pilih file PDF, DOC, atau DOCX.',
            'file_path.mimes' => 'Format file materi harus PDF, DOC, atau DOCX.',
            'file_path.max' => 'Ukuran file materi terlalu besar. Maksimal 10 MB. Silakan kompres atau pilih file yang lebih kecil.',
            'mata_pelajaran_id.exists' => 'Mata pelajaran yang dipilih tidak valid',
            'level_id.exists' => 'Level yang dipilih tidak valid',
            'cover_path.image' => 'Cover buku harus berupa gambar.',
            'cover_path.mimes' => 'Format cover buku harus JPG, JPEG, PNG, atau WEBP.',
            'cover_path.max' => 'Ukuran cover buku terlalu besar. Maksimal 5 MB. Silakan kompres atau pilih gambar yang lebih kecil.',
            'jumlah_halaman.integer' => 'Jumlah halaman harus berupa angka.',
            'jumlah_halaman.min' => 'Jumlah halaman minimal 1.',
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
        $filePath = $materi->file_path;
        $coverPath = $materi->cover_path;

        DB::transaction(function () use ($materi) {
            $sesiBacaIds = DB::table('sesi_baca')
                ->where('materi_id', $materi->id)
                ->pluck('id');

            DB::table('log_akses_materi')->where('materi_id', $materi->id)->delete();
            DB::table('log_perintah_suara')->whereIn('sesi_id', $sesiBacaIds)->delete();
            DB::table('sesi_baca')->where('materi_id', $materi->id)->delete();
            DB::table('rak_buku')->where('materi_id', $materi->id)->delete();
            DB::table('catatan_siswa')->where('materi_id', $materi->id)->update(['materi_id' => null]);
            DB::table('kuis')->where('materi_id', $materi->id)->update(['materi_id' => null]);

            $materi->delete();
        });

        // Delete files only after the database delete succeeds.
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        if ($coverPath && Storage::disk('public')->exists($coverPath)) {
            Storage::disk('public')->delete($coverPath);
        }

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json(['message' => 'Materi berhasil dihapus!']);
        }

        return redirect()->route('materi.index')
            ->with('success', 'Materi berhasil dihapus!');
    }

    private function isSiswaApiRequest(): bool
    {
        $user = Auth::user();

        return (request()->wantsJson() || request()->is('api/*'))
            && $user
            && $user->peran === 'siswa';
    }
}
