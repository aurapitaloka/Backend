<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $ulasan = Ulasan::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('id', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('rating', 'like', "%{$search}%")
                        ->orWhere('isi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.ulasan.index', compact('ulasan', 'search'));
    }

    public function exportCsv()
    {
        $filename = 'ulasan-' . now()->format('Ymd-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Nama', 'Email', 'Rating', 'Ulasan', 'Tanggal']);

            Ulasan::orderByDesc('created_at')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->nama,
                        $row->email,
                        $row->rating,
                        $row->isi,
                        optional($row->created_at)->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Ulasan $ulasan)
    {
        $ulasan->delete();

        return redirect()
            ->route('ulasan.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
