<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Materi;
use App\Models\MataPelajaran;
use App\Services\PdfCompressionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    private const PDF_TARGET_MAX_KB = 10240;

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
        $maxUploadKb = $this->getServerUploadLimitInKb();

        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'level_id' => 'nullable|exists:level,id',
            'tipe_konten' => 'required|in:teks,file',
            'konten_teks' => 'nullable|string|required_if:tipe_konten,teks',
            'file_path' => "nullable|file|mimes:pdf,doc,docx|max:{$maxUploadKb}|required_if:tipe_konten,file",
            'pdf_page_selection' => 'nullable|string',
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
            'file_path.max' => 'Ukuran file materi melebihi batas upload server.',
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
            $storedFile = $this->storeMateriFile(
                $request->file('file_path'),
                $request->input('pdf_page_selection')
            );
            $validated['file_path'] = $storedFile['path'];
            $validated['pdf_page_selection'] = $request->input('pdf_page_selection');
            if ($storedFile['page_count'] !== null) {
                $validated['jumlah_halaman'] = $storedFile['page_count'];
            }
        } else {
            $validated['pdf_page_selection'] = null;
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
        $maxUploadKb = $this->getServerUploadLimitInKb();

        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'level_id' => 'nullable|exists:level,id',
            'tipe_konten' => 'required|in:teks,file',
            'konten_teks' => 'nullable|string|required_if:tipe_konten,teks',
            'file_path' => "nullable|file|mimes:pdf,doc,docx|max:{$maxUploadKb}",
            'pdf_page_selection' => 'nullable|string',
            'cover_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'status_aktif' => 'boolean',
        ], [
            'judul.required' => 'Judul wajib diisi',
            'tipe_konten.required' => 'Tipe konten wajib dipilih',
            'konten_teks.required_if' => 'Konten teks wajib diisi jika tipe konten adalah teks',
            'file_path.file' => 'File materi tidak valid. Pilih file PDF, DOC, atau DOCX.',
            'file_path.mimes' => 'Format file materi harus PDF, DOC, atau DOCX.',
            'file_path.max' => 'Ukuran file materi melebihi batas upload server.',
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
            $storedFile = $this->storeMateriFile(
                $request->file('file_path'),
                $request->input('pdf_page_selection')
            );
            $newFilePath = $storedFile['path'];

            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $validated['file_path'] = $newFilePath;
            $validated['pdf_page_selection'] = $request->input('pdf_page_selection');
            if ($storedFile['page_count'] !== null) {
                $validated['jumlah_halaman'] = $storedFile['page_count'];
            }
        } else {
            // Keep existing file if not uploading new one
            $validated['file_path'] = $materi->file_path;
            $validated['pdf_page_selection'] = $materi->pdf_page_selection;
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

    private function storeMateriFile($file, $pageSelection = null): array
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(300);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $originalSize = $file->getSize() ?: 0;
        $maxTargetBytes = self::PDF_TARGET_MAX_KB * 1024;
        $pdfService = app(PdfCompressionService::class);

        if ($extension !== 'pdf' && $originalSize > $maxTargetBytes) {
            throw ValidationException::withMessages([
                'file_path' => 'File DOC/DOCX maksimal 10 MB. Kompres otomatis hanya diterapkan untuk PDF.',
            ]);
        }

        if ($extension !== 'pdf' && $pageSelection) {
            throw ValidationException::withMessages([
                'file_path' => 'Pilihan halaman hanya berlaku untuk file PDF.',
            ]);
        }

        if ($extension !== 'pdf') {
            $fileName = time() . '_' . $file->getClientOriginalName();
            return [
                'path' => $file->storeAs('materi', $fileName, 'public'),
                'page_count' => null,
            ];
        }

        $tempDirectory = storage_path('app/tmp/pdf-compression');
        if (!is_dir($tempDirectory)) {
            mkdir($tempDirectory, 0755, true);
        }

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBaseName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $baseName) ?: 'materi';
        $uniqueToken = time() . '_' . uniqid();

        $sourcePath = $tempDirectory . DIRECTORY_SEPARATOR . $uniqueToken . '_source.pdf';
        $selectedPath = $tempDirectory . DIRECTORY_SEPARATOR . $uniqueToken . '_selected.pdf';
        $targetPath = $tempDirectory . DIRECTORY_SEPARATOR . $uniqueToken . '_compressed.pdf';
        $bestEffortPath = $sourcePath . '.best';

        $file->move($tempDirectory, basename($sourcePath));

        $workingPath = $sourcePath;
        $pageCount = $pdfService->getPageCount($sourcePath);
        $selectedPages = $this->parseSelectedPages($pageSelection);

        if (!empty($selectedPages)) {
            if ($pageCount === null) {
                @unlink($sourcePath);

                throw ValidationException::withMessages([
                    'file_path' => 'Jumlah halaman PDF tidak bisa dibaca. Pastikan Ghostscript terpasang dengan benar.',
                ]);
            }

            $invalidPage = collect($selectedPages)->first(fn (int $page) => $page < 1 || $page > $pageCount);
            if ($invalidPage !== null) {
                @unlink($sourcePath);

                throw ValidationException::withMessages([
                    'file_path' => "Pilihan halaman tidak valid. Halaman {$invalidPage} berada di luar total {$pageCount} halaman.",
                ]);
            }

            if (count($selectedPages) < $pageCount) {
                $selectionResult = $pdfService->extractSelectedPages(
                    $sourcePath,
                    $selectedPath,
                    $this->buildGhostscriptPageList($selectedPages)
                );

                if (!($selectionResult['success'] ?? false)) {
                    @unlink($sourcePath);
                    @unlink($selectedPath);

                    throw ValidationException::withMessages([
                        'file_path' => 'PDF gagal dipotong sesuai halaman yang dicentang.',
                    ]);
                }

                $workingPath = $selectedPath;
            }

            $pageCount = count($selectedPages);
        }

        $workingSize = filesize($workingPath) ?: 0;

        if ($workingSize > $maxTargetBytes) {
            $result = $pdfService->compressToTarget(
                $workingPath,
                $targetPath,
                $maxTargetBytes
            );

            $finalPath = $result['output_path'] ?? $workingPath;
            $finalSize = $result['final_size'] ?? 0;

            if (!is_file($finalPath) || $finalSize > $maxTargetBytes) {
                @unlink($sourcePath);
                @unlink($selectedPath);
                @unlink($targetPath);
                @unlink($bestEffortPath);

                $message = ($result['tool'] ?? null) === null
                    ? 'PDF di atas 10 MB diterima, tetapi server belum memiliki tool kompres PDF otomatis. Pasang Ghostscript atau upload PDF yang lebih kecil.'
                    : 'PDF gagal dikompres hingga 10 MB. Coba kompres PDF-nya sedikit lagi atau kurangi kualitas gambar di dalam PDF.';

                throw ValidationException::withMessages([
                    'file_path' => $message,
                ]);
            }
        } else {
            $finalPath = $workingPath;
            $finalSize = $workingSize;
        }

        $storedFileName = time() . '_' . $safeBaseName . '.pdf';
        $storedPath = 'materi/' . $storedFileName;

        Storage::disk('public')->put($storedPath, file_get_contents($finalPath));

        @unlink($sourcePath);
        @unlink($selectedPath);
        @unlink($targetPath);
        @unlink($bestEffortPath);

        if ($pageCount === null && is_file($finalPath)) {
            $pageCount = $pdfService->getPageCount($finalPath);
        }

        return [
            'path' => $storedPath,
            'page_count' => $pageCount,
        ];
    }

    private function parseSelectedPages(?string $pageSelection): array
    {
        if ($pageSelection === null || trim($pageSelection) === '') {
            return [];
        }

        $parts = preg_split('/\s*,\s*/', trim($pageSelection)) ?: [];
        $pages = [];

        foreach ($parts as $part) {
            if ($part === '' || !ctype_digit($part)) {
                continue;
            }

            $page = (int) $part;
            if ($page > 0) {
                $pages[] = $page;
            }
        }

        $pages = array_values(array_unique($pages));
        sort($pages);

        return $pages;
    }

    private function buildGhostscriptPageList(array $pages): string
    {
        sort($pages);

        $ranges = [];
        $start = null;
        $previous = null;

        foreach ($pages as $page) {
            if ($start === null) {
                $start = $page;
                $previous = $page;
                continue;
            }

            if ($page === $previous + 1) {
                $previous = $page;
                continue;
            }

            $ranges[] = $start === $previous ? (string) $start : "{$start}-{$previous}";
            $start = $page;
            $previous = $page;
        }

        if ($start !== null) {
            $ranges[] = $start === $previous ? (string) $start : "{$start}-{$previous}";
        }

        return implode(',', $ranges);
    }

    private function getServerUploadLimitInKb(): int
    {
        $uploadMax = $this->convertIniSizeToKb((string) ini_get('upload_max_filesize'));
        $postMax = $this->convertIniSizeToKb((string) ini_get('post_max_size'));
        $effectiveLimit = min($uploadMax, $postMax);

        return $effectiveLimit > 0 ? $effectiveLimit : self::PDF_TARGET_MAX_KB;
    }

    private function convertIniSizeToKb(string $value): int
    {
        $value = trim($value);

        if ($value === '') {
            return self::PDF_TARGET_MAX_KB;
        }

        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return match ($unit) {
            'g' => (int) round($number * 1024 * 1024),
            'm' => (int) round($number * 1024),
            'k' => (int) round($number),
            default => (int) round($number / 1024),
        };
    }
}
