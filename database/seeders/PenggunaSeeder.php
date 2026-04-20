<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah superadmin sudah ada
        $existingSuperadmin = Pengguna::where('email', 'superadmin@ruma.com')->first();
        
        if (!$existingSuperadmin) {
            // Buat pengguna superadmin
            $superadmin = Pengguna::create([
                'nama' => 'Super Admin',
                'email' => 'superadmin@ruma.com',
                'kata_sandi' => Hash::make('password'),
                'peran' => 'guru',
                'status_aktif' => true,
            ]);

            // Buat data guru untuk superadmin
            Guru::create([
                'pengguna_id' => $superadmin->id,
                'nama_sekolah' => 'Sekolah Ruma',
            ]);

            $this->command->info('Superadmin berhasil dibuat!');
            $this->command->info('Email: superadmin@ruma.com');
            $this->command->info('Password: password');
        } else {
            $this->command->info('Superadmin sudah ada di database.');
        }
    }
}
