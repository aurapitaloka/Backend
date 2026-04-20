<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\KuisHasil;
use App\Models\KuisJawaban;
use App\Models\KuisPertanyaan;
use App\Models\KuisOpsi;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class KuisController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $kuis = Kuis::with('materi')
            ->withCount('pertanyaan')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('id', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('materi', function ($materiQuery) use ($search) {
                            $materiQuery->where('judul', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.kuis.index', compact('kuis', 'search'));
    }

    public function create()
    {
        $materiList = Materi::where('status_aktif', true)
            ->orderBy('judul')
            ->get();

        return view('dashboard.kuis.create', compact('materiList'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        DB::transaction(function () use ($validated, $request) {
            $kuis = Kuis::create([
                'materi_id' => $validated['materi_id'] ?? null,
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'status_aktif' => $request->boolean('status_aktif'),
                'dibuat_oleh' => Auth::id(),
            ]);

            $this->storePertanyaan($kuis->id, $validated['pertanyaan'], $request);
        });

        return redirect()->route('kuis.index')
            ->with('success', 'Kuis berhasil dibuat.');
    }

    public function show(Kuis $kui)
    {
        $kui->load(['materi', 'pertanyaan.opsi']);
        return view('dashboard.kuis.show', ['kuis' => $kui]);
    }

    public function edit(Kuis $kui)
    {
        $kui->load('pertanyaan.opsi');
        $materiList = Materi::where('status_aktif', true)
            ->orderBy('judul')
            ->get();

        return view('dashboard.kuis.edit', ['kuis' => $kui, 'materiList' => $materiList]);
    }

    public function update(Request $request, Kuis $kui)
    {
        $validated = $this->validatePayload($request, $kui);

        DB::transaction(function () use ($kui, $validated, $request) {
            $existingAudioPaths = $kui->pertanyaan()
                ->pluck('audio_path', 'id')
                ->all();

            $kui->update([
                'materi_id' => $validated['materi_id'] ?? null,
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'status_aktif' => $request->boolean('status_aktif'),
            ]);

            KuisPertanyaan::where('kuis_id', $kui->id)->delete();
            $this->storePertanyaan($kui->id, $validated['pertanyaan'], $request, $existingAudioPaths);
        });

        return redirect()->route('kuis.index')
            ->with('success', 'Kuis berhasil diperbarui.');
    }

    public function destroy(Kuis $kui)
    {
        $kui->delete();

        return redirect()->route('kuis.index')
            ->with('success', 'Kuis berhasil dihapus.');
    }

    public function hasilIndex(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $hasil = KuisHasil::query()
            ->with(['kuis.materi', 'jawaban.pertanyaan'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('id', 'like', "%{$search}%")
                        ->orWhere('skor', 'like', "%{$search}%")
                        ->orWhere('total_benar', 'like', "%{$search}%")
                        ->orWhere('total_pertanyaan', 'like', "%{$search}%")
                        ->orWhereHas('kuis', function ($kuisQuery) use ($search) {
                            $kuisQuery->where('judul', 'like', "%{$search}%")
                                ->orWhereHas('materi', function ($materiQuery) use ($search) {
                                    $materiQuery->where('judul', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->orderByDesc('selesai_at')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.kuis.hasil', compact('hasil', 'search'));
    }

    public function hasilShow(KuisHasil $hasil)
    {
        $hasil->load(['kuis.materi', 'jawaban.pertanyaan']);
        return view('dashboard.kuis.hasil-show', compact('hasil'));
    }

    public function hasilUpdate(Request $request, KuisHasil $hasil)
    {
        $hasil->load(['jawaban.pertanyaan']);

        $updates = $request->input('koreksi', []);
        foreach ($hasil->jawaban as $jawaban) {
            $p = $jawaban->pertanyaan;
            if (!$p || !in_array($p->tipe, ['essay', 'speaking'], true)) {
                continue;
            }
            $data = $updates[$jawaban->id] ?? null;
            if (!$data) {
                continue;
            }
            $status = $data['status_koreksi'] ?? $jawaban->status_koreksi;
            $skorAuto = isset($data['skor_auto']) ? (int) $data['skor_auto'] : $jawaban->skor_auto;
            $threshold = $p->tipe === 'speaking' ? 80 : 70;
            $benar = ($status === 'approved') && $skorAuto >= $threshold;

            $jawaban->update([
                'status_koreksi' => $status,
                'skor_auto' => $skorAuto,
                'benar' => $benar,
            ]);
        }

        $totalPertanyaan = $hasil->jawaban->count();
        $totalBenar = $hasil->jawaban->where('benar', true)->count();
        $skor = $totalPertanyaan > 0 ? (int) round(($totalBenar / $totalPertanyaan) * 100) : 0;
        $hasil->update([
            'total_benar' => $totalBenar,
            'total_pertanyaan' => $totalPertanyaan,
            'skor' => $skor,
        ]);

        return redirect()
            ->route('kuis.hasil.show', $hasil->id)
            ->with('success', 'Koreksi disimpan.');
    }

    private function validatePayload(Request $request, ?Kuis $kuis = null): array
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:200',
            'materi_id' => [
                'nullable',
                Rule::exists('materi', 'id')->where('status_aktif', true),
            ],
            'deskripsi' => 'nullable|string',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*.id' => 'nullable|integer',
            'pertanyaan.*.teks' => 'required|string',
            'pertanyaan.*.tipe' => 'required|in:pilihan,essay,listening,speaking',
            'pertanyaan.*.benar' => 'nullable|in:A,B,C,D',
            'pertanyaan.*.opsi' => 'nullable|array',
            'pertanyaan.*.opsi.A' => 'nullable|string',
            'pertanyaan.*.opsi.B' => 'nullable|string',
            'pertanyaan.*.opsi.C' => 'nullable|string',
            'pertanyaan.*.opsi.D' => 'nullable|string',
            'pertanyaan.*.jawaban_teks' => 'nullable|string',
            'pertanyaan.*.keyword' => 'nullable|string|max:255',
            'pertanyaan.*.audio_text' => 'nullable|string',
            'pertanyaan.*.bahasa' => 'nullable|in:id-ID,en-US',
            'pertanyaan_audio' => 'nullable|array',
            'pertanyaan_audio.*' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
        ], [
            'judul.required' => 'Judul kuis wajib diisi.',
            'pertanyaan.required' => 'Minimal harus ada 1 pertanyaan.',
            'pertanyaan.*.teks.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.*.tipe.required' => 'Tipe soal wajib dipilih.',
            'pertanyaan_audio.*.file' => 'Audio soal tidak valid. Pilih file MP3, WAV, atau OGG.',
            'pertanyaan_audio.*.mimes' => 'Format audio soal harus MP3, WAV, atau OGG.',
            'pertanyaan_audio.*.max' => 'Ukuran audio soal terlalu besar. Maksimal 10 MB per file. Silakan kompres atau pilih audio yang lebih kecil.',
        ]);

        $validator->after(function ($validator) use ($request, $kuis) {
            $pertanyaanList = $request->input('pertanyaan', []);
            $audioFiles = $request->file('pertanyaan_audio', []);
            $existingAudioPaths = $kuis
                ? $kuis->pertanyaan()->pluck('audio_path', 'id')->all()
                : [];

            foreach ($pertanyaanList as $idx => $item) {
                $tipe = $item['tipe'] ?? 'pilihan';
                $existingAudioPath = null;
                if (!empty($item['id']) && array_key_exists((int) $item['id'], $existingAudioPaths)) {
                    $existingAudioPath = $existingAudioPaths[(int) $item['id']];
                }

                if (in_array($tipe, ['pilihan', 'listening'], true)) {
                    if (empty($item['benar']) || empty($item['opsi']['A']) || empty($item['opsi']['B']) || empty($item['opsi']['C']) || empty($item['opsi']['D'])) {
                        $validator->errors()->add("pertanyaan.$idx", 'Soal pilihan ganda/listening harus punya opsi A-D dan jawaban benar.');
                    }
                    if ($tipe === 'listening') {
                        $audioText = $item['audio_text'] ?? '';
                        if (!$audioText && empty($audioFiles[$idx]) && empty($existingAudioPath)) {
                            $validator->errors()->add("pertanyaan.$idx", 'Soal listening harus punya audio (file) atau teks TTS.');
                        }
                    }
                }
                if ($tipe === 'essay') {
                    if (empty($item['jawaban_teks']) || empty($item['keyword'])) {
                        $validator->errors()->add("pertanyaan.$idx", 'Soal essay harus punya jawaban contoh dan keyword.');
                    }
                }
                if ($tipe === 'speaking') {
                    $audioText = $item['audio_text'] ?? '';
                    if (empty($item['jawaban_teks'])) {
                        $validator->errors()->add("pertanyaan.$idx", 'Soal speaking harus punya jawaban target.');
                    }
                    if (!$audioText && empty($audioFiles[$idx]) && empty($existingAudioPath)) {
                        $validator->errors()->add("pertanyaan.$idx", 'Soal speaking harus punya audio contoh (file) atau teks TTS.');
                    }
                }
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function storePertanyaan(int $kuisId, array $pertanyaanList, Request $request, array $existingAudioPaths = []): void
    {
        $order = 1;
        foreach ($pertanyaanList as $idx => $item) {
            $tipe = $item['tipe'] ?? 'pilihan';
            $audioPath = null;
            if (in_array($tipe, ['listening', 'speaking'], true)) {
                if (!empty($item['id']) && array_key_exists((int) $item['id'], $existingAudioPaths)) {
                    $audioPath = $existingAudioPaths[(int) $item['id']];
                }

                $audioFiles = $request->file('pertanyaan_audio', []);
                if (isset($audioFiles[$idx]) && $audioFiles[$idx]) {
                    $file = $audioFiles[$idx];
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $audioPath = $file->storeAs('kuis_audio', $fileName, 'public');
                }
            }

            $pertanyaan = KuisPertanyaan::create([
                'kuis_id' => $kuisId,
                'pertanyaan' => $item['teks'],
                'urutan' => $order,
                'tipe' => $tipe,
                'jawaban_teks' => $item['jawaban_teks'] ?? null,
                'keyword' => $item['keyword'] ?? null,
                'audio_path' => $audioPath,
                'audio_text' => $item['audio_text'] ?? null,
                'bahasa' => $item['bahasa'] ?? null,
            ]);

            if (in_array($tipe, ['pilihan', 'listening'], true)) {
                foreach (['A', 'B', 'C', 'D'] as $label) {
                    if (!isset($item['opsi'][$label])) {
                        continue;
                    }
                    KuisOpsi::create([
                        'pertanyaan_id' => $pertanyaan->id,
                        'label' => $label,
                        'teks' => $item['opsi'][$label],
                        'benar' => ($item['benar'] ?? '') === $label,
                    ]);
                }
            }

            $order += 1;
        }
    }
}
