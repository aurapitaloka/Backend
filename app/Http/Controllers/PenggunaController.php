<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengguna = Pengguna::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.pengguna.pengguna', compact('pengguna'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email|max:150',
            'kata_sandi' => 'required|string|min:6',
            'peran' => 'required|in:siswa,guru',
            'status_aktif' => 'boolean',
            'nama_sekolah' => 'nullable|string|max:150',
            'jenjang' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'kata_sandi.required' => 'Kata sandi wajib diisi',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter',
            'peran.required' => 'Peran wajib dipilih',
            'peran.in' => 'Peran harus siswa atau guru',
        ]);

        // Create pengguna
        $pengguna = Pengguna::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'kata_sandi' => Hash::make($validated['kata_sandi']),
            'peran' => $validated['peran'],
            'status_aktif' => $request->has('status_aktif') ? true : false,
        ]);

        // Create related record based on role
        if ($validated['peran'] === 'siswa') {
            Siswa::create([
                'pengguna_id' => $pengguna->id,
                'nama_sekolah' => $validated['nama_sekolah'] ?? null,
                'jenjang' => $validated['jenjang'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
            ]);
        } elseif ($validated['peran'] === 'guru') {
            Guru::create([
                'pengguna_id' => $pengguna->id,
                'nama_sekolah' => $validated['nama_sekolah'] ?? null,
            ]);
        }

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengguna = Pengguna::with(['siswa', 'guru'])->findOrFail($id);
        return view('dashboard.pengguna.show', compact('pengguna'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengguna = Pengguna::with(['siswa', 'guru'])->findOrFail($id);
        return view('dashboard.pengguna.edit', compact('pengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengguna = Pengguna::with(['siswa', 'guru'])->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('pengguna')->ignore($id), 'max:150'],
            'kata_sandi' => 'nullable|string|min:6',
            'peran' => 'required|in:siswa,guru',
            'status_aktif' => 'boolean',
            'nama_sekolah' => 'nullable|string|max:150',
            'jenjang' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter',
            'peran.required' => 'Peran wajib dipilih',
            'peran.in' => 'Peran harus siswa atau guru',
        ]);

        // Update pengguna
        $updateData = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'peran' => $validated['peran'],
            'status_aktif' => $request->has('status_aktif') ? true : false,
        ];

        if (!empty($validated['kata_sandi'])) {
            $updateData['kata_sandi'] = Hash::make($validated['kata_sandi']);
        }

        $pengguna->update($updateData);

        // Update or create related record based on role
        if ($validated['peran'] === 'siswa') {
            if ($pengguna->siswa) {
                $pengguna->siswa->update([
                    'nama_sekolah' => $validated['nama_sekolah'] ?? null,
                    'jenjang' => $validated['jenjang'] ?? null,
                    'catatan' => $validated['catatan'] ?? null,
                ]);
            } else {
                // Delete guru record if exists
                if ($pengguna->guru) {
                    $pengguna->guru->delete();
                }
                // Create siswa record
                Siswa::create([
                    'pengguna_id' => $pengguna->id,
                    'nama_sekolah' => $validated['nama_sekolah'] ?? null,
                    'jenjang' => $validated['jenjang'] ?? null,
                    'catatan' => $validated['catatan'] ?? null,
                ]);
            }
        } elseif ($validated['peran'] === 'guru') {
            if ($pengguna->guru) {
                $pengguna->guru->update([
                    'nama_sekolah' => $validated['nama_sekolah'] ?? null,
                ]);
            } else {
                // Delete siswa record if exists
                if ($pengguna->siswa) {
                    $pengguna->siswa->delete();
                }
                // Create guru record
                Guru::create([
                    'pengguna_id' => $pengguna->id,
                    'nama_sekolah' => $validated['nama_sekolah'] ?? null,
                ]);
            }
        }

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        // Delete related records
        if ($pengguna->siswa) {
            $pengguna->siswa->delete();
        }
        if ($pengguna->guru) {
            $pengguna->guru->delete();
        }

        $pengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
