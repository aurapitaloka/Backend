<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $levels = Level::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('id', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        if (request()->expectsJson()) {
            return response()->json($levels);
        }

        return view('dashboard.level.index', compact('levels', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.level.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:level,nama',
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'boolean',
        ], [
            'nama.required' => 'Nama level wajib diisi',
            'nama.unique' => 'Nama level sudah ada',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif') ? true : false;

        Level::create($validated);

        if ($request->expectsJson()) {
            return response()->json(Level::where('nama', $validated['nama'])->first(), 201);
        }

        return redirect()->route('level.index')
            ->with('success', 'Level berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $level = Level::findOrFail($id);
        if (request()->expectsJson()) {
            return response()->json($level);
        }

        return view('dashboard.level.show', compact('level'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $level = Level::findOrFail($id);
        return view('dashboard.level.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $level = Level::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:level,nama,' . $id,
            'deskripsi' => 'nullable|string',
            'status_aktif' => 'boolean',
        ], [
            'nama.required' => 'Nama level wajib diisi',
            'nama.unique' => 'Nama level sudah ada',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif') ? true : false;

        $level->update($validated);

        if ($request->expectsJson()) {
            return response()->json($level);
        }

        return redirect()->route('level.index')
            ->with('success', 'Level berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        if (request()->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('level.index')
            ->with('success', 'Level berhasil dihapus!');
    }
}
