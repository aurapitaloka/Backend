<?php

namespace App\Http\Controllers;

use App\Models\Panduan;
use Illuminate\Http\Request;

class ApiPanduanController extends Controller
{
    public function index()
    {
        try {
            $data = Panduan::all(); // 🔥 ambil semua data tanpa error kolom

            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data panduan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}