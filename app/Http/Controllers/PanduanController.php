<?php

namespace App\Http\Controllers;

use App\Models\Panduan;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search', ''));

        $data = Panduan::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('id', 'like', "%{$search}%")
                        ->orWhere('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhere('tag', 'like', "%{$search}%")
                        ->orWhere('urutan', 'like', "%{$search}%");
                });
            })
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.panduan.index', compact('data', 'search'));
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

    public function show($id)
{
    $data = Panduan::findOrFail($id);
    return view('dashboard.panduan.show', compact('data'));
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
