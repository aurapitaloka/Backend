<?php

namespace App\Http\Controllers;

use App\Models\CatatanSiswa;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCatatanController extends Controller
{
    /**
     * List catatan for authenticated siswa.
     */
    public function index(Request $request)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $perPage = (int) $request->get('per_page', 8);

        $catatan = CatatanSiswa::with('materi')
            ->where('pengguna_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        if ($request->boolean('with_materi_list')) {
            $materiList = Materi::where('status_aktif', true)
                ->when(($user->siswa && $user->siswa->level_id), function ($query) use ($user) {
                    $query->where('level_id', $user->siswa->level_id);
                })
                ->orderBy('judul')
                ->get(['id', 'judul', 'level_id']);

            return response()->json([
                'catatan' => $catatan,
                'materi_list' => $materiList,
            ]);
        }

        return response()->json($catatan);
    }

    /**
     * Store new catatan for authenticated siswa.
     */
    public function store(Request $request)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'materi_id' => 'nullable|exists:materi,id',
            'isi' => 'required|string|max:5000',
        ], [
            'isi.required' => 'Catatan tidak boleh kosong.',
        ]);

        $catatan = CatatanSiswa::create([
            'pengguna_id' => $user->id,
            'materi_id' => $validated['materi_id'] ?? null,
            'isi' => $validated['isi'],
        ]);

        $catatan->load('materi');

        return response()->json([
            'message' => 'Catatan berhasil disimpan.',
            'data' => $catatan,
        ], 201);
    }

    /**
     * Delete catatan (owner only).
     */
    public function destroy(CatatanSiswa $catatan)
    {
        $user = $this->requireSiswa();
        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($catatan->pengguna_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $catatan->delete();

        return response()->json(['message' => 'Catatan dihapus.']);
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
}
