<?php

namespace App\Jobs;

use App\Models\ProjectImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class OptimizeProjectImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    /** @var array<int, int> */
    public array $backoff = [10, 30, 60];

    /** Target widths for the two generated variants. */
    private const VARIANTS = [
        'thumb' => 480,
        'md' => 1200,
    ];

    public function __construct(public readonly ProjectImage $image) {}

    public function handle(): void
    {
        $disk = Storage::disk('public');
        $source = $this->image->path;

        if (! $disk->exists($source)) {
            throw new RuntimeException("Source image missing: {$source}");
        }

        $absolute = $disk->path($source);
        $info = @getimagesize($absolute);

        if ($info === false) {
            throw new RuntimeException("Unreadable image: {$source}");
        }

        [$width, $height, $type] = $info;

        $srcImage = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($absolute),
            IMAGETYPE_PNG => imagecreatefrompng($absolute),
            IMAGETYPE_WEBP => imagecreatefromwebp($absolute),
            default => throw new RuntimeException("Unsupported image type: {$type}"),
        };

        if ($srcImage === false) {
            throw new RuntimeException("Failed to decode: {$source}");
        }

        try {
            foreach (self::VARIANTS as $suffix => $targetWidth) {
                $this->writeVariant($srcImage, $width, $height, $suffix, $targetWidth, $disk, $source);
            }
        } finally {
            imagedestroy($srcImage);
        }

        Log::info('Project image optimized', [
            'project_image_id' => $this->image->id,
            'source' => $source,
            'variants' => array_keys(self::VARIANTS),
        ]);
    }

    private function writeVariant(
        \GdImage $src,
        int $srcWidth,
        int $srcHeight,
        string $suffix,
        int $targetWidth,
        FilesystemAdapter $disk,
        string $source,
    ): void {
        if ($srcWidth <= $targetWidth) {
            $dstWidth = $srcWidth;
            $dstHeight = $srcHeight;
        } else {
            $dstWidth = $targetWidth;
            $dstHeight = (int) round($srcHeight * ($targetWidth / $srcWidth));
        }

        $dst = imagecreatetruecolor($dstWidth, $dstHeight);

        // Preserve alpha for PNG sources.
        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        imagecopyresampled(
            $dst, $src,
            0, 0, 0, 0,
            $dstWidth, $dstHeight,
            $srcWidth, $srcHeight,
        );

        $targetPath = preg_replace('/\.[^.]+$/', "-{$suffix}.webp", $source);
        $absoluteTarget = $disk->path($targetPath);

        // Ensure the directory exists (it will, but be defensive).
        if (! is_dir(dirname($absoluteTarget))) {
            mkdir(dirname($absoluteTarget), 0755, true);
        }

        imagewebp($dst, $absoluteTarget, quality: 82);
        imagedestroy($dst);
    }

    public function failed(?Throwable $e): void
    {
        Log::error('Project image optimization failed', [
            'project_image_id' => $this->image->id,
            'path' => $this->image->path,
            'error' => $e?->getMessage(),
        ]);
    }
}
