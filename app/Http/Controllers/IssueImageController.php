<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class IssueImageController
{
    /**
     * Images are embedded in public GitHub issues, so they are served without authentication.
     */
    public function show(string $image): StreamedResponse
    {
        $path = 'issue-images/'.$image.'.webp';

        abort_unless(Storage::disk()->exists($path), 404);

        return Storage::disk()->response($path, $image.'.webp', headers: [
            'Content-Type' => 'image/webp',
            'Content-Security-Policy' => "default-src 'none'; sandbox",
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
