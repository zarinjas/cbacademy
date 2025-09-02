<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Routing\UrlGenerator;

class VideoController extends Controller
{
    /**
     * Stream a local video file stored in storage/app/videos
     * URL must be a signed route (valid for 30 minutes)
     */
    public function stream(Request $request)
    {
    // Simplified: allow direct requests to /videos/stream?file=... so local
    // development and simple deployments can stream without signed URLs.
    // NOTE: This is intentionally permissive for development; consider
    // re-adding auth/verification for production deployments.

        $file = $request->query('file');
        if (!$file) abort(404);

        $path = 'videos/' . ltrim($file, '/');

        // First, try the configured 'local' disk (project uses storage/app/private by default).
        $fullPath = null;
        if (Storage::disk('local')->exists($path)) {
            $fullPath = Storage::disk('local')->path($path);
        }

        // Fallback: some environments or deploy scripts place files under storage/app/videos
        // instead of storage/app/private/videos — check that location too.
        if (is_null($fullPath)) {
            $fallback = storage_path('app/videos/' . ltrim($file, '/'));
            if (file_exists($fallback)) {
                $fullPath = $fallback;
            }
        }

        if (!$fullPath || !file_exists($fullPath)) {
            abort(404);
        }

        // If configured, prefer letting the webserver serve the file via X-Accel-Redirect (nginx)
        // or X-Sendfile (apache). This is opt-in via .env to avoid changing behavior unexpectedly.
        // Set VIDEO_ACCEL_REDIRECT=true and VIDEO_ACCEL_REDIRECT_DRIVER=nginx (or apache) in .env to enable.
        $useAccel = env('VIDEO_ACCEL_REDIRECT', false);
        $driver = env('VIDEO_ACCEL_REDIRECT_DRIVER', 'nginx');

        $headers = [
            'Content-Type' => 'video/mp4',
            'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
            'Cache-Control' => 'no-store, private',
            'Accept-Ranges' => 'bytes',
        ];

        if ($useAccel) {
            // If nginx: instruct it to serve an internal location. The nginx config should
            // map /protected_videos/ to the real storage path and be marked 'internal'.
            if ($driver === 'nginx') {
                $internalPath = '/protected_videos/' . ltrim($file, '/');
                return response('', 200, array_merge($headers, [
                    'X-Accel-Redirect' => $internalPath,
                ]));
            }

            // If apache with X-Sendfile support, pass the real filesystem path.
            if ($driver === 'apache') {
                return response('', 200, array_merge($headers, [
                    'X-Sendfile' => $fullPath,
                ]));
            }
        }
    $size = filesize($fullPath);
    $fm = fopen($fullPath, 'rb');
        $start = 0;
        $end = $size - 1;

        $headers = [
            'Content-Type' => 'video/mp4',
            'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
            'Cache-Control' => 'no-store, private',
            'Accept-Ranges' => 'bytes',
        ];

        // Handle HTTP Range header for seeking
        if ($request->headers->has('Range')) {
            $range = $request->header('Range'); // e.g., bytes=0-499
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                if ($matches[2] !== '') {
                    $end = intval($matches[2]);
                }
            }
            $length = $end - $start + 1;
            $response = new StreamedResponse(function () use ($fm, $start, $end) {
                $buffer = 1024 * 8;
                fseek($fm, $start);
                $bytesLeft = $end - $start + 1;
                while ($bytesLeft > 0 && !feof($fm)) {
                    $read = ($bytesLeft > $buffer) ? $buffer : $bytesLeft;
                    echo fread($fm, $read);
                    flush();
                    $bytesLeft -= $read;
                }
                fclose($fm);
            }, 206, array_merge($headers, [
                'Content-Range' => "bytes {$start}-{$end}/{$size}",
                'Content-Length' => $length,
            ]));

            return $response;
        }

        // Full file
        $response = new StreamedResponse(function () use ($fm) {
            $buffer = 1024 * 8;
            while (!feof($fm)) {
                echo fread($fm, $buffer);
                flush();
            }
            fclose($fm);
        }, 200, array_merge($headers, [
            'Content-Length' => $size,
        ]));

        return $response;
    }
}
