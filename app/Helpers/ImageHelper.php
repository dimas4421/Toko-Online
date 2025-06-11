<?php

namespace App\Helpers;

class ImageHelper
{
    public static function uploadAndResize($file, $directory, $fileName, $width = null, $height = null)
    {
        $destinationPath = public_path($directory);

        // Buat direktori jika belum ada
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $image = null;

        // Tentukan metode pembuatan gambar berdasarkan ekstensi file
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
    $image = @imagecreatefrompng($file->getRealPath()); // Tambahkan '@'
    break;

            case 'gif':
                $image = imagecreatefromgif($file->getRealPath());
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        // Resize gambar jika lebar ditentukan
        if ($width) {
            $oldWidth = imagesx($image);
            $oldHeight = imagesy($image);
            $aspectRatio = $oldWidth / $oldHeight;

            // Hitung tinggi jika tidak diberikan, agar menjaga aspek rasio
            if (!$height) {
                $height = intval($width / $aspectRatio);
            }

            $newImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);

            imagedestroy($image);
            $image = $newImage;
        }

        $savePath = $destinationPath . '/' . $fileName;

        // Simpan gambar
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($image, $savePath);
                break;
            case 'png':
                imagepng($image, $savePath);
                break;
            case 'gif':
                imagegif($image, $savePath);
                break;
        }

        imagedestroy($image);
        return $fileName;
    }
}
