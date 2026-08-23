<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Redimensionne (si besoin) et compresse une image uploadée avant de la stocker.
     * Retombe sur un stockage brut si le format n'est pas géré par GD.
     */
    public function store(UploadedFile $file, string $directory, int $maxWidth = 1600, int $quality = 82, string $disk = 'public'): string
    {
        $sourcePath = $file->getRealPath();
        $info = $sourcePath ? @getimagesize($sourcePath) : false;

        if (! $info) {
            return $file->store($directory, $disk);
        }

        [$width, $height, $type] = $info;

        $source = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($sourcePath),
            default => false,
        };

        if (! $source) {
            return $file->store($directory, $disk);
        }

        $ratio = min(1, $maxWidth / $width);
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        $keepPng = $type === IMAGETYPE_PNG;

        if ($keepPng) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }

        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($source);

        ob_start();
        if ($keepPng) {
            imagepng($resized, null, 6);
            $extension = 'png';
        } else {
            imagejpeg($resized, null, $quality);
            $extension = 'jpg';
        }
        $contents = ob_get_clean();
        imagedestroy($resized);

        $path = trim($directory, '/').'/'.Str::random(40).'.'.$extension;
        Storage::disk($disk)->put($path, $contents);

        return $path;
    }
}
