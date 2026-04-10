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

        $userId = Auth::id();

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
        $userId = Auth::id();

        $exists = RakBuku::where('pengguna_id', $userId)->where('materi_id', $materiId)->exists();

        return response()->json(['in_rak' => (bool)$exists]);
    }

    // List user's rak buku (paginated)
    public function index(Request $request)
    {
        $userId = Auth::id();
        $perPage = (int) $request->get('per_page', 10);

        $list = RakBuku::with('materi')
            ->where('pengguna_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($list);
    }
}
