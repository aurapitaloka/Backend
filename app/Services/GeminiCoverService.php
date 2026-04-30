<?php

namespace App\Services;

use App\Exceptions\GeminiCoverException;
use Illuminate\Support\Facades\Http;

class GeminiCoverService
{
    public function generateBookCover(array $context): array
    {
        $apiKey = (string) config('services.gemini.api_key');
        $model = (string) config('services.gemini.image_model', 'gemini-2.5-flash-image');

        if ($apiKey === '') {
            throw new GeminiCoverException('GEMINI_API_KEY belum dikonfigurasi.');
        }

        $prompt = $this->buildPrompt($context);

        $response = Http::timeout(120)
            ->withHeaders([
                'x-goog-api-key' => $apiKey,
            ])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'contents' => [[
                    'parts' => [[
                        'text' => $prompt,
                    ]],
                ]],
            ]);

        if ($response->failed()) {
            $rawMessage = $response->json('error.message')
                ?: $response->json('message')
                ?: 'Permintaan ke Gemini gagal.';

            throw new GeminiCoverException(
                $this->buildFriendlyErrorMessage($response->status(), $rawMessage, $model),
                $this->normalizeHttpStatus($response->status())
            );
        }

        $parts = data_get($response->json(), 'candidates.0.content.parts', []);

        foreach ($parts as $part) {
            $inlineData = $part['inlineData'] ?? $part['inline_data'] ?? null;
            $base64Data = $inlineData['data'] ?? null;
            $mimeType = $inlineData['mimeType'] ?? $inlineData['mime_type'] ?? null;

            if (!$base64Data || !$mimeType) {
                continue;
            }

            $binary = base64_decode($base64Data, true);
            if ($binary === false) {
                continue;
            }

            return [
                'prompt' => $prompt,
                'binary' => $binary,
                'mime_type' => $mimeType,
            ];
        }

        throw new GeminiCoverException('Gemini tidak mengembalikan gambar cover. Coba generate ulang beberapa saat lagi.');
    }

    private function buildPrompt(array $context): string
    {
        $judul = trim((string) ($context['judul'] ?? ''));
        $mataPelajaran = trim((string) ($context['mata_pelajaran'] ?? ''));
        $level = trim((string) ($context['level'] ?? ''));
        $deskripsi = trim((string) ($context['deskripsi'] ?? ''));
        $promptTambahan = trim((string) ($context['prompt_tambahan'] ?? ''));

        $lines = [
            'Create a portrait 3:4 educational book cover illustration for students.',
            'The design must look clean, friendly, modern, and suitable for a learning app.',
            'Place the title as the main readable text on the cover.',
            'Use large, clear typography and strong visual hierarchy.',
            'Avoid brand logos, watermarks, unsafe content, and cluttered layouts.',
            'Output a single polished cover image.',
        ];

        if ($judul !== '') {
            $lines[] = "Book title: {$judul}.";
        }

        if ($mataPelajaran !== '') {
            $lines[] = "Subject: {$mataPelajaran}.";
        }

        if ($level !== '') {
            $lines[] = "Student level: {$level}.";
        }

        if ($deskripsi !== '') {
            $lines[] = "Material description: {$deskripsi}.";
        }

        if ($promptTambahan !== '') {
            $lines[] = "Additional style direction: {$promptTambahan}.";
        }

        return implode("\n", $lines);
    }

    private function buildFriendlyErrorMessage(int $status, string $rawMessage, string $model): string
    {
        $normalized = strtolower($rawMessage);

        if (
            $status === 429
            || str_contains($normalized, 'quota')
            || str_contains($normalized, 'rate limit')
            || str_contains($normalized, 'resource_exhausted')
        ) {
            return "Kuota Gemini untuk generate cover sedang habis atau belum aktif pada project ini. "
                . "Cek billing dan rate limit di Google AI Studio, atau coba lagi nanti. "
                . "Model yang sedang dipakai: {$model}.";
        }

        if ($status === 403 || str_contains($normalized, 'permission')) {
            return "Akses ke model Gemini ditolak. Pastikan API key benar, project aktif, dan model {$model} tersedia untuk akun kamu.";
        }

        if ($status === 404 || str_contains($normalized, 'not found')) {
            return "Model Gemini {$model} tidak ditemukan. Cek nilai GEMINI_IMAGE_MODEL di file .env.";
        }

        if ($status >= 500) {
            return 'Layanan Gemini sedang bermasalah. Coba beberapa saat lagi.';
        }

        return $rawMessage;
    }

    private function normalizeHttpStatus(int $status): int
    {
        if ($status >= 400 && $status <= 599) {
            return $status;
        }

        return 422;
    }
}
