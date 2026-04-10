<?php

namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\KuisHasil;
use App\Models\KuisJawaban;
use App\Models\Materi;
use App\Models\SesiBaca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiKuisController extends Controller
{
    /**
     * List quizzes available for the authenticated siswa.
     */
    public function index(Request $request)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $levelId = $user->siswa?->level_id;

        $kuisAktif = Kuis::with(['materi'])
            ->withCount('pertanyaan')
            ->where('status_aktif', true)
            ->when($levelId, function ($query) use ($levelId) {
                $query->where(function ($inner) use ($levelId) {
                    $inner->whereNull('materi_id')
                        ->orWhereHas('materi', function ($materiQuery) use ($levelId) {
                            $materiQuery->where('level_id', $levelId);
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $kuisUmum = $kuisAktif->whereNull('materi_id')->values();
        $kuisMateri = $kuisAktif->whereNotNull('materi_id')->values();

        $materiIdsForProgress = $kuisMateri->pluck('materi_id')
            ->filter()
            ->unique()
            ->values();

        $progressRows = SesiBaca::where('pengguna_id', $user->id)
            ->when($materiIdsForProgress->count() > 0, function ($query) use ($materiIdsForProgress) {
                $query->whereIn('materi_id', $materiIdsForProgress->all());
            })
            ->select(
                'materi_id',
                DB::raw('MAX(COALESCE(progres_persen, 0)) as progres_persen'),
                DB::raw('MAX(CASE WHEN selesai IS NULL THEN 0 ELSE 1 END) as selesai_flag')
            )
            ->groupBy('materi_id')
            ->get();

        $progressMap = [];
        $completedMateriIds = [];
        foreach ($progressRows as $row) {
            $progressMap[$row->materi_id] = [
                'progres' => (int) ($row->progres_persen ?? 0),
                'selesai' => (int) ($row->selesai_flag ?? 0),
            ];
            if (($row->selesai_flag ?? 0) || ((int) ($row->progres_persen ?? 0) >= 80)) {
                $completedMateriIds[] = $row->materi_id;
            }
        }

        return response()->json([
            'kuis_umum' => $kuisUmum,
            'kuis_materi' => $kuisMateri,
            'progress_map' => $progressMap,
            'completed_materi_ids' => $completedMateriIds,
        ]);
    }

    /**
     * Show a general quiz (non-materi).
     */
    public function show(Kuis $kuis)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if (!$kuis->status_aktif) {
            return response()->json(['message' => 'Kuis tidak tersedia'], 404);
        }

        if ($kuis->materi_id) {
            return response()->json([
                'message' => 'Kuis ini terhubung dengan materi.',
                'materi_id' => $kuis->materi_id,
                'redirect_to' => "/api/dashboard-siswa/materi/{$kuis->materi_id}/kuis",
            ], 409);
        }

        $kuis->load('pertanyaan.opsi');

        return response()->json([
            'kuis' => $this->sanitizeKuisForSiswa($kuis),
        ]);
    }

    /**
     * Show a quiz bound to a materi.
     */
    public function showMateri(Materi $materi)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($block = $this->ensureMateriLevelAccess($user, $materi)) {
            return $block;
        }

        if (!$materi->status_aktif) {
            return response()->json(['message' => 'Materi tidak tersedia'], 404);
        }

        if (!$this->isMateriCompleted($user->id, $materi->id)) {
            return response()->json([
                'message' => 'Selesaikan materi terlebih dahulu sebelum mengerjakan kuis.',
                'materi_id' => $materi->id,
            ], 403);
        }

        $kuis = Kuis::with('pertanyaan.opsi')
            ->where('materi_id', $materi->id)
            ->where('status_aktif', true)
            ->first();

        if (!$kuis) {
            return response()->json(['message' => 'Kuis tidak ditemukan'], 404);
        }

        return response()->json([
            'materi' => [
                'id' => $materi->id,
                'judul' => $materi->judul,
                'level_id' => $materi->level_id,
            ],
            'kuis' => $this->sanitizeKuisForSiswa($kuis),
        ]);
    }

    /**
     * Submit answers for general quiz.
     */
    public function submitKuis(Request $request, Kuis $kuis)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if (!$kuis->status_aktif) {
            return response()->json(['message' => 'Kuis tidak tersedia'], 404);
        }

        if ($kuis->materi_id) {
            return response()->json([
                'message' => 'Kuis ini terhubung dengan materi.',
                'materi_id' => $kuis->materi_id,
                'redirect_to' => "/api/dashboard-siswa/materi/{$kuis->materi_id}/kuis",
            ], 409);
        }

        $request->validate([
            'jawaban' => 'array',
            'jawaban_teks' => 'array',
        ]);

        $kuis->load('pertanyaan.opsi');
        $result = $this->storeKuisHasil($request, $kuis, $user->id);

        return response()->json([
            'message' => 'Kuis selesai.',
            'hasil_id' => $result['hasil_id'],
            'skor' => $result['skor'],
            'total_benar' => $result['total_benar'],
            'total_pertanyaan' => $result['total_pertanyaan'],
        ]);
    }

    /**
     * Submit answers for materi quiz.
     */
    public function submitMateri(Request $request, Materi $materi)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($block = $this->ensureMateriLevelAccess($user, $materi)) {
            return $block;
        }

        if (!$materi->status_aktif) {
            return response()->json(['message' => 'Materi tidak tersedia'], 404);
        }

        if (!$this->isMateriCompleted($user->id, $materi->id)) {
            return response()->json([
                'message' => 'Selesaikan materi terlebih dahulu sebelum mengerjakan kuis.',
                'materi_id' => $materi->id,
            ], 403);
        }

        $request->validate([
            'jawaban' => 'array',
            'jawaban_teks' => 'array',
        ]);

        $kuis = Kuis::with('pertanyaan.opsi')
            ->where('materi_id', $materi->id)
            ->where('status_aktif', true)
            ->first();

        if (!$kuis) {
            return response()->json(['message' => 'Kuis tidak ditemukan'], 404);
        }

        $result = $this->storeKuisHasil($request, $kuis, $user->id);

        return response()->json([
            'message' => 'Kuis selesai.',
            'hasil_id' => $result['hasil_id'],
            'skor' => $result['skor'],
            'total_benar' => $result['total_benar'],
            'total_pertanyaan' => $result['total_pertanyaan'],
        ]);
    }

    /**
     * List quiz history for authenticated siswa.
     */
    public function riwayat(Request $request)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $levelId = $user->siswa?->level_id;
        $kuisSort = $request->get('kuis_sort', 'latest');
        $perPage = (int) $request->get('per_page', 8);

        $query = DB::table('kuis_hasil')
            ->join('kuis', 'kuis.id', '=', 'kuis_hasil.kuis_id')
            ->leftJoin('materi', 'materi.id', '=', 'kuis.materi_id')
            ->leftJoin('kuis_jawaban', 'kuis_jawaban.kuis_hasil_id', '=', 'kuis_hasil.id')
            ->where('kuis_hasil.pengguna_id', $user->id)
            ->when($levelId, function ($query) use ($levelId) {
                $query->where(function ($inner) use ($levelId) {
                    $inner->whereNull('kuis.materi_id')
                        ->orWhere('materi.level_id', $levelId);
                });
            })
            ->select(
                'kuis_hasil.id as hasil_id',
                'kuis.judul as kuis_judul',
                'kuis.materi_id as materi_id',
                'materi.judul as materi_judul',
                'kuis_hasil.skor',
                'kuis_hasil.total_benar',
                'kuis_hasil.total_pertanyaan',
                'kuis_hasil.selesai_at',
                DB::raw("MAX(CASE WHEN kuis_jawaban.status_koreksi = 'pending' THEN 1 ELSE 0 END) as has_pending")
            )
            ->groupBy('kuis_hasil.id', 'kuis.judul', 'kuis.materi_id', 'materi.judul', 'kuis_hasil.skor', 'kuis_hasil.total_benar', 'kuis_hasil.total_pertanyaan', 'kuis_hasil.selesai_at');

        if ($kuisSort === 'score') {
            $query->orderByDesc('kuis_hasil.skor')->orderByDesc('kuis_hasil.selesai_at');
        } else {
            $query->orderByDesc('kuis_hasil.selesai_at');
        }

        $riwayat = $query->paginate($perPage);

        return response()->json($riwayat);
    }

    /**
     * Show quiz history detail for authenticated siswa.
     */
    public function riwayatShow($hasilId)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $hasil = KuisHasil::with(['kuis.materi', 'jawaban.pertanyaan', 'jawaban.opsi'])
            ->where('id', $hasilId)
            ->where('pengguna_id', $user->id)
            ->firstOrFail();

        if ($hasil->kuis && $hasil->kuis->materi) {
            if ($block = $this->ensureMateriLevelAccess($user, $hasil->kuis->materi)) {
                return $block;
            }
        }

        $payload = $hasil->toArray();
        foreach ($payload['jawaban'] ?? [] as $idx => $jawaban) {
            if (isset($jawaban['opsi']['benar'])) {
                unset($payload['jawaban'][$idx]['opsi']['benar']);
            }
        }

        return response()->json([
            'hasil' => $payload,
        ]);
    }

    private function requireSiswa()
    {
        $user = Auth::user();
        if (!$user || $user->peran !== 'siswa') {
            return null;
        }

        $user->load('siswa');

        return $user;
    }

    private function ensureMateriLevelAccess($user, Materi $materi)
    {
        $levelId = $user->siswa?->level_id;
        if (!$levelId) {
            return null;
        }

        if ((int) ($materi->level_id ?? 0) !== (int) $levelId) {
            return response()->json([
                'message' => 'Materi ini bukan untuk kelas kamu.',
                'materi_id' => $materi->id,
            ], 403);
        }

        return null;
    }

    private function isMateriCompleted(int $userId, int $materiId): bool
    {
        $row = SesiBaca::where('pengguna_id', $userId)
            ->where('materi_id', $materiId)
            ->select(
                DB::raw('MAX(COALESCE(progres_persen, 0)) as progres_persen'),
                DB::raw('MAX(CASE WHEN selesai IS NULL THEN 0 ELSE 1 END) as selesai_flag')
            )
            ->first();

        if (!$row) {
            return false;
        }

        return (bool) ($row->selesai_flag ?? 0) || ((int) ($row->progres_persen ?? 0) >= 80);
    }

    private function storeKuisHasil(Request $request, Kuis $kuis, int $userId): array
    {
        $jawaban = $request->input('jawaban', []);
        $jawabanTeks = $request->input('jawaban_teks', []);
        $totalPertanyaan = $kuis->pertanyaan->count();
        $totalBenar = 0;

        $hasil = KuisHasil::create([
            'kuis_id' => $kuis->id,
            'pengguna_id' => $userId,
            'total_pertanyaan' => $totalPertanyaan,
            'total_benar' => 0,
            'skor' => 0,
            'selesai_at' => now(),
        ]);

        $similarity = function (string $a, string $b): int {
            $normalize = function (string $s): string {
                $s = mb_strtolower($s);
                $s = preg_replace('/[^\p{L}\p{N}\s-]/u', ' ', $s);
                $s = preg_replace('/\s+/', ' ', $s);
                return trim($s);
            };
            $a = $normalize($a);
            $b = $normalize($b);
            if ($a === '' || $b === '') {
                return 0;
            }
            $maxLen = max(mb_strlen($a), mb_strlen($b));
            if ($maxLen === 0) {
                return 0;
            }
            $dist = levenshtein($a, $b);
            $score = (1 - ($dist / $maxLen)) * 100;
            return (int) max(0, min(100, round($score)));
        };

        foreach ($kuis->pertanyaan as $pertanyaan) {
            if (in_array($pertanyaan->tipe, ['pilihan', 'listening'], true)) {
                $selectedId = $jawaban[$pertanyaan->id] ?? null;
                $opsi = $pertanyaan->opsi->firstWhere('id', (int) $selectedId);
                $benar = $opsi ? (bool) $opsi->benar : false;

                if ($benar) {
                    $totalBenar += 1;
                }

                KuisJawaban::create([
                    'kuis_hasil_id' => $hasil->id,
                    'pertanyaan_id' => $pertanyaan->id,
                    'opsi_id' => $opsi?->id,
                    'benar' => $benar,
                ]);
                continue;
            }

            $teks = trim((string) ($jawabanTeks[$pertanyaan->id] ?? ''));
            $skorAuto = null;
            $benar = false;

            if ($pertanyaan->tipe === 'essay') {
                if ($pertanyaan->keyword) {
                    $keywords = array_filter(array_map('trim', explode(',', $pertanyaan->keyword)));
                    if (count($keywords) > 0) {
                        $normalized = mb_strtolower($teks);
                        $match = 0;
                        foreach ($keywords as $kw) {
                            if ($kw !== '' && str_contains($normalized, mb_strtolower($kw))) {
                                $match += 1;
                            }
                        }
                        $skorAuto = (int) round(($match / count($keywords)) * 100);
                        $benar = false;
                    }
                }
            } elseif ($pertanyaan->tipe === 'speaking') {
                $target = (string) ($pertanyaan->jawaban_teks ?? '');
                $skorAuto = $similarity($teks, $target);
                $benar = false;
            }

            KuisJawaban::create([
                'kuis_hasil_id' => $hasil->id,
                'pertanyaan_id' => $pertanyaan->id,
                'opsi_id' => null,
                'benar' => $benar,
                'jawaban_teks' => $teks,
                'skor_auto' => $skorAuto,
                'status_koreksi' => 'pending',
            ]);
        }

        $skor = $totalPertanyaan > 0 ? (int) round(($totalBenar / $totalPertanyaan) * 100) : 0;
        $hasil->update([
            'total_benar' => $totalBenar,
            'skor' => $skor,
        ]);

        return [
            'hasil_id' => $hasil->id,
            'skor' => $skor,
            'total_benar' => $totalBenar,
            'total_pertanyaan' => $totalPertanyaan,
        ];
    }

    private function sanitizeKuisForSiswa(Kuis $kuis): array
    {
        $payload = $kuis->toArray();

        foreach ($payload['pertanyaan'] ?? [] as $idx => $pertanyaan) {
            unset($payload['pertanyaan'][$idx]['jawaban_teks']);
            unset($payload['pertanyaan'][$idx]['keyword']);
            if (!empty($payload['pertanyaan'][$idx]['opsi'])) {
                foreach ($payload['pertanyaan'][$idx]['opsi'] as $j => $opsi) {
                    unset($payload['pertanyaan'][$idx]['opsi'][$j]['benar']);
                }
            }
        }

        return $payload;
    }
}
