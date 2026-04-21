<?php

namespace App\Services;

class PdfCompressionService
{
    private const COMPRESSION_TIMEOUT_SECONDS = 300;

    public function compressToTarget(string $sourcePath, string $targetPath, int $targetBytes): array
    {
        $this->extendExecutionTime();

        if (!is_file($sourcePath)) {
            return [
                'success' => false,
                'compressed' => false,
                'tool' => null,
                'message' => 'Source PDF tidak ditemukan.',
                'output_path' => $sourcePath,
                'final_size' => 0,
            ];
        }

        $originalSize = filesize($sourcePath) ?: 0;
        $ghostscript = $this->findGhostscriptBinary();

        if (!$ghostscript) {
            return [
                'success' => $originalSize <= $targetBytes,
                'compressed' => false,
                'tool' => null,
                'message' => 'Ghostscript tidak tersedia di server.',
                'output_path' => $sourcePath,
                'final_size' => $originalSize,
            ];
        }

        $profiles = [
            '/screen',
            '/ebook',
            '/default',
        ];

        foreach ($profiles as $profile) {
            @unlink($targetPath);

            [$exitCode, $errorOutput] = $this->runGhostscript(
                $ghostscript,
                $profile,
                $sourcePath,
                $targetPath
            );

            clearstatcache(true, $targetPath);

            if ($exitCode !== 0 || !is_file($targetPath)) {
                continue;
            }

            $compressedSize = filesize($targetPath) ?: 0;

            if ($compressedSize > 0 && $compressedSize <= $targetBytes) {
                return [
                    'success' => true,
                    'compressed' => $compressedSize < $originalSize,
                    'tool' => 'ghostscript',
                    'profile' => $profile,
                    'message' => null,
                    'output_path' => $targetPath,
                    'final_size' => $compressedSize,
                ];
            }

            if ($compressedSize > 0 && $compressedSize < $originalSize) {
                @copy($targetPath, $sourcePath . '.best');
            }
        }

        $bestEffortPath = $sourcePath . '.best';
        if (is_file($bestEffortPath)) {
            $bestEffortSize = filesize($bestEffortPath) ?: 0;

            return [
                'success' => $bestEffortSize <= $targetBytes,
                'compressed' => $bestEffortSize < $originalSize,
                'tool' => 'ghostscript',
                'profile' => 'best-effort',
                'message' => 'PDF berhasil diperkecil, tetapi masih di atas target ukuran.',
                'output_path' => $bestEffortPath,
                'final_size' => $bestEffortSize,
            ];
        }

        return [
            'success' => $originalSize <= $targetBytes,
            'compressed' => false,
            'tool' => 'ghostscript',
            'message' => $errorOutput ?? 'Kompresi PDF gagal dijalankan.',
            'output_path' => $sourcePath,
            'final_size' => $originalSize,
        ];
    }

    public function getPageCount(string $sourcePath): ?int
    {
        $this->extendExecutionTime();

        if (!is_file($sourcePath)) {
            return null;
        }

        $ghostscript = $this->findGhostscriptBinary();
        if (!$ghostscript) {
            return null;
        }

        $postScriptPath = $this->escapePathForPostScriptLiteral($sourcePath);
        $command = sprintf(
            '"%s" -q -dNOSAFER -dNODISPLAY -c "(%s) (r) file runpdfbegin pdfpagecount = quit"',
            $ghostscript,
            $postScriptPath
        );

        $output = [];
        $exitCode = 1;
        @exec($command . ' 2>&1', $output, $exitCode);

        if ($exitCode !== 0) {
            return null;
        }

        $lastLine = trim((string) end($output));
        if ($lastLine === '' || !ctype_digit($lastLine)) {
            return null;
        }

        return (int) $lastLine;
    }

    public function extractPageRange(
        string $sourcePath,
        string $targetPath,
        int $firstPage,
        int $lastPage
    ): array {
        $this->extendExecutionTime();

        if (!is_file($sourcePath)) {
            return [
                'success' => false,
                'tool' => null,
                'message' => 'Source PDF tidak ditemukan.',
                'output_path' => $sourcePath,
            ];
        }

        $ghostscript = $this->findGhostscriptBinary();
        if (!$ghostscript) {
            return [
                'success' => false,
                'tool' => null,
                'message' => 'Ghostscript tidak tersedia di server.',
                'output_path' => $sourcePath,
            ];
        }

        $command = sprintf(
            '"%s" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -dFirstPage=%d -dLastPage=%d -sOutputFile="%s" "%s"',
            $ghostscript,
            $firstPage,
            $lastPage,
            $targetPath,
            $sourcePath
        );

        $output = [];
        $exitCode = 1;
        @exec($command . ' 2>&1', $output, $exitCode);

        return [
            'success' => $exitCode === 0 && is_file($targetPath),
            'tool' => 'ghostscript',
            'message' => $exitCode === 0 ? null : trim(implode("\n", $output)),
            'output_path' => $targetPath,
        ];
    }

    public function extractSelectedPages(
        string $sourcePath,
        string $targetPath,
        string $pageList
    ): array {
        $this->extendExecutionTime();

        if (!is_file($sourcePath)) {
            return [
                'success' => false,
                'tool' => null,
                'message' => 'Source PDF tidak ditemukan.',
                'output_path' => $sourcePath,
            ];
        }

        $ghostscript = $this->findGhostscriptBinary();
        if (!$ghostscript) {
            return [
                'success' => false,
                'tool' => null,
                'message' => 'Ghostscript tidak tersedia di server.',
                'output_path' => $sourcePath,
            ];
        }

        $command = sprintf(
            '"%s" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sPageList=%s -sOutputFile="%s" "%s"',
            $ghostscript,
            $pageList,
            $targetPath,
            $sourcePath
        );

        $output = [];
        $exitCode = 1;
        @exec($command . ' 2>&1', $output, $exitCode);

        return [
            'success' => $exitCode === 0 && is_file($targetPath),
            'tool' => 'ghostscript',
            'message' => $exitCode === 0 ? null : trim(implode("\n", $output)),
            'output_path' => $targetPath,
        ];
    }

    private function findGhostscriptBinary(): ?string
    {
        $candidates = [
            'gswin64c',
            'gswin32c',
            'gs',
        ];

        foreach ($candidates as $candidate) {
            $command = PHP_OS_FAMILY === 'Windows'
                ? 'where.exe ' . $candidate . ' 2>NUL'
                : 'command -v ' . escapeshellarg($candidate) . ' 2>/dev/null';

            $output = [];
            $exitCode = 1;

            @exec($command, $output, $exitCode);

            if ($exitCode === 0 && !empty($output[0])) {
                return trim($output[0]);
            }
        }

        foreach ($this->getWindowsGhostscriptPaths() as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    private function getWindowsGhostscriptPaths(): array
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return [];
        }

        $matches = glob('C:\\Program Files\\gs\\*\\bin\\gswin64c.exe') ?: [];
        $matches32 = glob('C:\\Program Files (x86)\\gs\\*\\bin\\gswin32c.exe') ?: [];

        $allMatches = array_merge($matches, $matches32);

        usort($allMatches, static fn (string $a, string $b) => version_compare($b, $a));

        return $allMatches;
    }

    private function runGhostscript(
        string $binary,
        string $profile,
        string $sourcePath,
        string $targetPath
    ): array {
        $this->extendExecutionTime();

        $command = sprintf(
            '"%s" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=%s -dNOPAUSE -dQUIET -dBATCH -sOutputFile="%s" "%s"',
            $binary,
            $profile,
            $targetPath,
            $sourcePath
        );

        $output = [];
        $exitCode = 1;
        @exec($command . ' 2>&1', $output, $exitCode);

        return [$exitCode, trim(implode("\n", $output))];
    }

    private function extendExecutionTime(): void
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(self::COMPRESSION_TIMEOUT_SECONDS);
        }
    }

    private function escapePathForPostScriptLiteral(string $path): string
    {
        return str_replace(
            ['\\', '(', ')'],
            ['\\\\', '\\(', '\\)'],
            $path
        );
    }
}
