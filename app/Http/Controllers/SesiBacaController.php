<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SesiBaca;
use App\Models\Materi;

class SesiBacaController extends Controller
{
    /**
     * List reading sessions for authenticated user (paginated).
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $perPage = (int) $request->get('per_page', 10);

        $sessions = SesiBaca::with('materi')
            ->where('pengguna_id', $userId)
            ->orderBy('selesai', 'desc')
            ->paginate($perPage);

        return response()->json($sessions);
    }

    /**
     * Get last session for a specific materi (by materi id).
     */
    public function lastForMateri($materiId)
    {
        $userId = Auth::id();

        $session = SesiBaca::where('pengguna_id', $userId)
            ->where('materi_id', $materiId)
            ->orderBy('selesai', 'desc')
            ->first();

        if (! $session) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($session);
    }

    /**
     * Create a new reading session record.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'materi_id' => 'required|exists:materi,id',
            'mulai' => 'nullable|date',
            'selesai' => 'nullable|date',
            'durasi_detik' => 'nullable|integer|min:0',
            'halaman_terakhir' => 'nullable|integer|min:0',
            'progres_persen' => 'nullable|integer|min:0|max:100',
            'gunakan_gaze' => 'boolean',
            'gunakan_suara' => 'boolean',
        ]);

        $validated['pengguna_id'] = $userId;

        $sesi = SesiBaca::create($validated);

        return response()->json($sesi, 201);
    }

    /**
     * Create or update a session for the current user and materi (upsert).
     * Frontend can call this when page changes to save `halaman_terakhir`/`progres_persen`.
     */
    public function upsert(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'materi_id' => 'required|exists:materi,id',
            'mulai' => 'nullable|date',
            'selesai' => 'nullable|date',
            'durasi_detik' => 'nullable|integer|min:0',
            'halaman_terakhir' => 'nullable|integer|min:0',
            'progres_persen' => 'nullable|integer|min:0|max:100',
            'gunakan_gaze' => 'boolean',
            'gunakan_suara' => 'boolean',
        ]);

        $sesi = SesiBaca::firstOrNew([
            'pengguna_id' => $userId,
            'materi_id' => $validated['materi_id'],
        ]);

        // Only update provided fields
        foreach (['mulai','selesai','durasi_detik','halaman_terakhir','progres_persen','gunakan_gaze','gunakan_suara'] as $k) {
            if (array_key_exists($k, $validated)) {
                $sesi->{$k} = $validated[$k];
            }
        }

        $sesi->save();

        return response()->json($sesi);
    }

    /**
     * Update an existing session (only owner can update).
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $sesi = SesiBaca::where('id', $id)->where('pengguna_id', $userId)->firstOrFail();

        $validated = $request->validate([
            'mulai' => 'nullable|date',
            'selesai' => 'nullable|date',
            'durasi_detik' => 'nullable|integer|min:0',
            'halaman_terakhir' => 'nullable|integer|min:0',
            'progres_persen' => 'nullable|integer|min:0|max:100',
            'gunakan_gaze' => 'boolean',
            'gunakan_suara' => 'boolean',
        ]);

        $sesi->update($validated);

        return response()->json($sesi->fresh());
    }

    /**
     * Delete a session (owner only).
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        $sesi = SesiBaca::where('id', $id)->where('pengguna_id', $userId)->firstOrFail();
        $sesi->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
