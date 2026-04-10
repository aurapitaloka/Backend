<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pengguna;
use App\Models\Siswa;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the register form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'kata_sandi.required' => 'Kata sandi wajib diisi',
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if (!$pengguna) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ])->withInput($request->only('email'));
        }

        if (!Hash::check($request->kata_sandi, $pengguna->kata_sandi)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ])->withInput($request->only('email'));
        }

        if (!$pengguna->status_aktif) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ])->withInput($request->only('email'));
        }

        // Login user
        Auth::login($pengguna, $request->boolean('ingat_sandi'));

        $request->session()->regenerate();

        if ($pengguna->peran === 'siswa') {
            return redirect()->intended('/dashboard-siswa');
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Handle register request (web).
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:pengguna,email',
            'kata_sandi' => 'required|string|min:6|confirmed',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'kata_sandi.required' => 'Kata sandi wajib diisi',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter',
            'kata_sandi.confirmed' => 'Konfirmasi kata sandi tidak cocok',
        ]);

        $pengguna = DB::transaction(function () use ($validated) {
            $user = Pengguna::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'kata_sandi' => Hash::make($validated['kata_sandi']),
                'peran' => 'siswa',
                'status_aktif' => true,
            ]);

            Siswa::create([
                'pengguna_id' => $user->id,
                'nama_sekolah' => null,
                'jenjang' => null,
                'level_id' => null,
                'catatan' => null,
            ]);

            return $user;
        });

        Auth::login($pengguna);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard-siswa');
    }

    /**
     * Handle API login request (token-based).
     */
    public function apiLogin(Request $request): JsonResponse
    {
        $payload = [
            'email' => $request->input('email'),
            'kata_sandi' => $request->input('kata_sandi', $request->input('password')),
        ];

        $validated = validator($payload, [
            'email' => 'required|email',
            'kata_sandi' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'kata_sandi.required' => 'Kata sandi wajib diisi',
        ])->validate();

        $pengguna = Pengguna::where('email', $validated['email'])->first();

        if (!$pengguna || !Hash::check($validated['kata_sandi'], $pengguna->kata_sandi)) {
            return response()->json([
                'message' => 'Email atau kata sandi salah.'
            ], 401);
        }

        if (!$pengguna->status_aktif) {
            return response()->json([
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
            ], 403);
        }

        $token = $pengguna->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $pengguna->id,
                'nama' => $pengguna->nama,
                'email' => $pengguna->email,
                'peran' => $pengguna->peran,
            ]
        ]);
    }

    /**
     * Handle API logout request (token-based).
     */
    public function apiLogout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * Return authenticated API user.
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->load(['siswa', 'guru']);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Handle API register request (default role: siswa).
     */
    public function apiRegister(Request $request): JsonResponse
    {
        $payload = [
            'nama' => $request->input('nama', $request->input('name', $request->input('full_name'))),
            'email' => $request->input('email'),
            'kata_sandi' => $request->input('kata_sandi', $request->input('password')),
            'kata_sandi_konfirmasi' => $request->input(
                'kata_sandi_konfirmasi',
                $request->input(
                    'password_confirmation',
                    $request->input('confirm_password', $request->input('confirmPassword'))
                )
            ),
        ];

        if ($request->boolean('debug')) {
            Log::info('apiRegister payload fields', [
                'nama' => $payload['nama'] !== null && $payload['nama'] !== '',
                'email' => $payload['email'] !== null && $payload['email'] !== '',
                'kata_sandi' => $payload['kata_sandi'] !== null && $payload['kata_sandi'] !== '',
                'kata_sandi_konfirmasi' => $payload['kata_sandi_konfirmasi'] !== null && $payload['kata_sandi_konfirmasi'] !== '',
            ]);
        }

        $validated = validator($payload, [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:pengguna,email',
            'kata_sandi' => 'required|string|min:6',
            'kata_sandi_konfirmasi' => 'required|string|same:kata_sandi',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'kata_sandi.required' => 'Kata sandi wajib diisi',
            'kata_sandi.min' => 'Kata sandi minimal 6 karakter',
            'kata_sandi_konfirmasi.required' => 'Konfirmasi kata sandi wajib diisi',
            'kata_sandi_konfirmasi.same' => 'Konfirmasi kata sandi tidak cocok',
        ])->validate();

        $user = DB::transaction(function () use ($validated) {
            $pengguna = Pengguna::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'kata_sandi' => Hash::make($validated['kata_sandi']),
                'peran' => 'siswa',
                'status_aktif' => true,
            ]);

            Siswa::create([
                'pengguna_id' => $pengguna->id,
                'nama_sekolah' => null,
                'jenjang' => null,
                'level_id' => null,
                'catatan' => null,
            ]);

            return $pengguna;
        });

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'peran' => $user->peran,
            ],
        ], 201);
    }
}
