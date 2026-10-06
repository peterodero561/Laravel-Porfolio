<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

class OptimizeImageVariants
{
    private const VARIANTS = [
        'thumb' => 480,
        'md' => 1200,
    ];

    /**
     * Synchronously generates -thumb.webp and -md.webp next to $path.
     * Called inline from the admin form for the single hero/thumbnail image;
     * gallery images go through the queued job because there can be many.
     */
    public function run(string $path, string $disk = 'public'): void
    {
        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return;
        }

        $absolute = $storage->path($path);
        $info = @getimagesize($absolute);

        if ($info === false) {
            return;
        }

        [$width, $height, $type] = $info;

        $src = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($absolute),
            IMAGETYPE_PNG => imagecreatefrompng($absolute),
            IMAGETYPE_WEBP => imagecreatefromwebp($absolute),
            default => throw new RuntimeException("Unsupported image: {$path}"),
        };

        if ($src === false) {
            return;
        }

        try {
            foreach (self::VARIANTS as $suffix => $targetWidth) {
                if ($width <= $targetWidth) {
                    $dw = $width;
                    $dh = $height;
                } else {
                    $dw = $targetWidth;
                    $dh = (int) round($height * ($targetWidth / $width));
                }

                $dst = imagecreatetruecolor($dw, $dh);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $dw, $dh, $width, $height);

                $target = preg_replace('/\.[^.]+$/', "-{$suffix}.webp", $path);
                imagewebp($dst, $storage->path($target), quality: 82);
                imagedestroy($dst);
            }
        } finally {
            imagedestroy($src);
        }
    }
}
