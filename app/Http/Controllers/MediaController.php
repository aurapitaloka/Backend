<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function show(string $path)
    {
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($path);
        $mimeType = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';
        $fileName = basename($path);

        return Response::file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => (Str::startsWith($mimeType, ['application/pdf', 'image/']))
                ? 'inline; filename="' . $fileName . '"'
                : 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
