<?php

namespace App\Services;

use App\Exceptions\GeminiCoverException;
use Illuminate\Support\Facades\Http;

class HuggingFaceCoverService
{
    public function generateBookCover(array $context): array
    {
        $apiToken = (string) config('services.huggingface.api_token');
        $model = (string) config('services.huggingface.image_model', 'black-forest-labs/FLUX.1-schnell');
        $baseUrl = rtrim((string) config('services.huggingface.base_url', 'https://router.huggingface.co/hf-inference/models'), '/');

        if ($apiToken === '') {
            throw new GeminiCoverException('HF_API_TOKEN belum dikonfigurasi.');
        }

        $prompt = $this->buildPrompt($context);

        $response = Http::timeout(180)
            ->withToken($apiToken)
            ->accept('image/png')
            ->post("{$baseUrl}/{$model}", [
                'inputs' => $prompt,
                'parameters' => [
                    'num_inference_steps' => 4,
                    'guidance_scale' => 3.5,
                ],
            ]);

        if ($response->failed()) {
            $rawMessage = $response->json('error')
                ?: $response->json('message')
                ?: $response->body()
                ?: 'Permintaan ke Hugging Face gagal.';

            throw new GeminiCoverException(
                $this->buildFriendlyErrorMessage($response->status(), (string) $rawMessage, $model),
                $this->normalizeHttpStatus($response->status())
            );
        }

        $mimeType = (string) $response->header('Content-Type', 'image/png');
        $binary = $response->body();

        if ($binary === '' || !str_starts_with(strtolower($mimeType), 'image/')) {
            throw new GeminiCoverException('Hugging Face tidak mengembalikan gambar cover. Coba generate ulang beberapa saat lagi.');
        }

        return [
            'prompt' => $prompt,
            'binary' => $binary,
            'mime_type' => strtok($mimeType, ';') ?: 'image/png',
        ];
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
            'Place the book title as the main readable text on the cover.',
            'Use large, clear typography and strong visual hierarchy.',
            'Avoid brand logos, watermarks, unsafe content, cluttered layouts, or extra text outside the title.',
            'Output one polished cover image only.',
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
            $status === 402
            || $status === 429
            || str_contains($normalized, 'quota')
            || str_contains($normalized, 'rate limit')
            || str_contains($normalized, 'payment')
            || str_contains($normalized, 'credit')
        ) {
            return "Kuota Hugging Face untuk generate cover sedang habis atau belum aktif. "
                . "Cek inference usage atau billing akun Hugging Face kamu, lalu coba lagi. "
                . "Model yang sedang dipakai: {$model}.";
        }

        if ($status === 401 || $status === 403 || str_contains($normalized, 'unauthorized') || str_contains($normalized, 'forbidden')) {
            return 'Akses ke Hugging Face ditolak. Pastikan HF_API_TOKEN valid dan token memiliki izin inference.';
        }

        if ($status === 404 || str_contains($normalized, 'not found')) {
            return "Model Hugging Face {$model} tidak ditemukan. Cek nilai HF_IMAGE_MODEL di file .env.";
        }

        if ($status >= 500) {
            return 'Layanan Hugging Face sedang bermasalah. Coba beberapa saat lagi.';
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
