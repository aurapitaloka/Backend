<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;

class UlasanController extends Controller
{
    public function index()
    {
        $ulasan = Ulasan::orderByDesc('created_at')->paginate(15);

        return view('dashboard.ulasan.index', compact('ulasan'));
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
