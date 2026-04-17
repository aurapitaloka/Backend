<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RakBuku;
use App\Models\Materi;

class RakBukuController extends Controller
{
    // Add materi to user's rak buku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'materi_id' => 'required|exists:materi,id',
        ]);

        $user = Auth::user();
        $userId = $user->id;
        $user->loadMissing('siswa');

        $materi = Materi::findOrFail($validated['materi_id']);
        if (!$materi->status_aktif) {
            return response()->json(['message' => 'Materi tidak tersedia'], 404);
        }

        if ($user->peran === 'siswa' && $user->siswa?->level_id && (int) $materi->level_id !== (int) $user->siswa->level_id) {
            return response()->json(['message' => 'Materi ini bukan untuk kelas kamu.'], 403);
        }

        $existing = RakBuku::where('pengguna_id', $userId)
            ->where('materi_id', $validated['materi_id'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already in rak buku', 'data' => $existing]);
        }

        $entry = RakBuku::create([
            'pengguna_id' => $userId,
            'materi_id' => $validated['materi_id'],
        ]);

        $entry->load('materi');

        return response()->json($entry, 201);
    }

    // Remove materi from user's rak buku
    public function destroy($materiId)
    {
        $userId = Auth::id();

        $entry = RakBuku::where('pengguna_id', $userId)->where('materi_id', $materiId)->firstOrFail();
        $entry->delete();

        return response()->json(['message' => 'Deleted']);
    }

    // Check if materi is in user's rak buku
    public function status($materiId)
    {
        $user = Auth::user();
        $userId = $user->id;
        $user->loadMissing('siswa');

        $exists = RakBuku::where('pengguna_id', $userId)
            ->where('materi_id', $materiId)
            ->whereHas('materi', function ($query) use ($user) {
                $query->where('status_aktif', true)
                    ->when($user->peran === 'siswa' && $user->siswa?->level_id, function ($levelQuery) use ($user) {
                        $levelQuery->where('level_id', $user->siswa->level_id);
                    });
            })
            ->exists();

        return response()->json(['in_rak' => (bool)$exists]);
    }

    // List user's rak buku (paginated)
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $user->loadMissing('siswa');
        $perPage = (int) $request->get('per_page', 10);

        $list = RakBuku::with(['materi.mataPelajaran', 'materi.level'])
            ->where('pengguna_id', $userId)
            ->whereHas('materi', function ($query) use ($user) {
                $query->where('status_aktif', true)
                    ->when($user->peran === 'siswa' && $user->siswa?->level_id, function ($levelQuery) use ($user) {
                        $levelQuery->where('level_id', $user->siswa->level_id);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($list);
    }
}
