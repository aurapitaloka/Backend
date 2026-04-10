<?php

namespace App\Http\Controllers;

use App\Models\Panduan;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function index()
    {
        $data = Panduan::all();
        return view('dashboard.panduan.index', compact('data'));
    }

    public function create()
    {
        return view('dashboard.panduan.create');
    }

    public function store(Request $request)
    {
        // 1. validasi
        $request->validate([
            'judul' => 'required',
        ]);

        // 2. simpan ke database
        Panduan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tag' => $request->tag,
            'urutan' => $request->urutan ?? 1,
        ]);

        // 3. balik ke halaman index
        return redirect()->route('panduan.index')
            ->with('success', 'Panduan berhasil ditambahkan');
    }

    public function apiIndex()
{
    $data = \App\Models\Panduan::orderBy('urutan')->get();

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}

    public function edit($id)
{
    $data = Panduan::findOrFail($id);
    return view('dashboard.panduan.edit', compact('data'));
}

    public function update(Request $request, $id)
{
    $request->validate([
        'judul' => 'required',
    ]);

    $panduan = Panduan::findOrFail($id);

    $panduan->update([
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'tag' => $request->tag,
        'urutan' => $request->urutan ?? 1,
    ]);

    return redirect()->route('panduan.index')
        ->with('success', 'Panduan berhasil diupdate');
}

    public function destroy($id)
{
    $panduan = Panduan::findOrFail($id);
    $panduan->delete();

    return redirect()->route('panduan.index')
        ->with('success', 'Panduan berhasil dihapus');
}
}