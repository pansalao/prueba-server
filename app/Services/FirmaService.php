<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FirmaService
{
    /**
     * Transforma una imagen de firma a PNG con fondo transparente.
     *
     * Implementa el algoritmo:
     *  1. Carga la imagen con GD
     *  2. Binarización por umbral (High-threshold pixel separation)
     *     → Todo lo que supere cierto nivel de oscuridad LOCAL = tinta negra pura (#000000)
     *     → Todo lo demás = transparente absoluto (canal alfa)
     *  3. trimImage equivalente en GD → escanea desde los 4 bordes hacia adentro
     *     y recorta los píxeles 100% transparentes
     *  4. Padding mínimo (95% canvas utilization, 1:1 square aspect ratio)
     *
     * @param  UploadedFile|string  $imagen  Archivo subido o ruta/blob binario
     * @return string  Datos binarios del PNG resultante (transparent background)
     */
    public static function maikol_callate($imagen): string
    {
        // --- 1. Resolver fuente de imagen ---
        if ($imagen instanceof UploadedFile) {
            $rutaTemp = $imagen->getRealPath();
            $deleteTmp = false;
        } elseif (is_string($imagen) && file_exists($imagen)) {
            $rutaTemp = $imagen;
            $deleteTmp = false;
        } elseif (is_string($imagen)) {
            $rutaTemp = tempnam(sys_get_temp_dir(), 'firma_');
            file_put_contents($rutaTemp, $imagen);
            $deleteTmp = true;
        } else {
            throw new \InvalidArgumentException('La imagen debe ser un UploadedFile, ruta válida o blob binario.');
        }

        try {
            // --- 2. Leer metadatos ---
            $info = getimagesize($rutaTemp);
            if (!$info) {
                throw new \RuntimeException('No se pudo leer la imagen.');
            }

            $mime   = $info['mime'];
            $width  = $info[0];
            $height = $info[1];

            // --- 3. Crear recurso GD ---
            $src = match ($mime) {
                'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($rutaTemp),
                'image/png'               => @imagecreatefrompng($rutaTemp),
                'image/gif'               => @imagecreatefromgif($rutaTemp),
                'image/webp'              => @imagecreatefromwebp($rutaTemp),
                'image/bmp'               => @imagecreatefrombmp($rutaTemp),
                default => throw new \RuntimeException("Formato no soportado: {$mime}"),
            };

            if (!$src) {
                throw new \RuntimeException('GD no pudo abrir la imagen.');
            }

            // --- 4. Redimensionar a máximo 1200px (alta resolución) ---
            $maxDim = 1200;
            if ($width > $maxDim || $height > $maxDim) {
                $ratio     = min($maxDim / $width, $maxDim / $height);
                $newWidth  = (int) round($width  * $ratio);
                $newHeight = (int) round($height * $ratio);

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($src);
                $src    = $resized;
                $width  = $newWidth;
                $height = $newHeight;
            }

            // --- 5. Construcción del Mapa de Iluminación Local (Fondo Adaptativo) ---
            // Reducción extrema → las líneas finas (tinta) desaparecen, quedan las manchas de sombra
            $factor      = 20;
            $smallWidth  = max(1, (int) ($width  / $factor));
            $smallHeight = max(1, (int) ($height / $factor));

            $bgSmall = imagecreatetruecolor($smallWidth, $smallHeight);
            imagecopyresampled($bgSmall, $src, 0, 0, 0, 0, $smallWidth, $smallHeight, $width, $height);

            // Escalar de vuelta al tamaño original → suavizado extremo = mapa de iluminación
            $bgMap = imagecreatetruecolor($width, $height);
            imagecopyresampled($bgMap, $bgSmall, 0, 0, 0, 0, $width, $height, $smallWidth, $smallHeight);
            imagedestroy($bgSmall);

            // Desenfoque adicional para eliminar artefactos del mapa
            imagefilter($bgMap, IMG_FILTER_GAUSSIAN_BLUR);
            imagefilter($bgMap, IMG_FILTER_GAUSSIAN_BLUR);
            imagefilter($bgMap, IMG_FILTER_GAUSSIAN_BLUR);

            // --- 6. Binarización (thresholdImage equivalente) ---
            // Decisión binaria pura: tinta (#000000) o transparente (alfa puro)
            // High-threshold pixel separation: diff > INK_THRESHOLD → píxel de tinta
            $INK_THRESHOLD = 22; // Umbral de diferencia de luminancia local

            $dest = imagecreatetruecolor($width, $height);
            imagealphablending($dest, false);
            imagesavealpha($dest, true);

            // Llenar con transparencia total
            $fullyTransparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
            imagefill($dest, 0, 0, $fullyTransparent);

            $inkBlack = imagecolorallocatealpha($dest, 0, 0, 0, 0); // #000000, 100% opaco

            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    // Luminancia del píxel original
                    $rgb1 = imagecolorat($src, $x, $y);
                    $l1   = 0.299 * (($rgb1 >> 16) & 0xFF)
                          + 0.587 * (($rgb1 >>  8) & 0xFF)
                          + 0.114 * ( $rgb1        & 0xFF);

                    // Luminancia del mapa de fondo (sombra local)
                    $rgb2 = imagecolorat($bgMap, $x, $y);
                    $l2   = 0.299 * (($rgb2 >> 16) & 0xFF)
                          + 0.587 * (($rgb2 >>  8) & 0xFF)
                          + 0.114 * ( $rgb2        & 0xFF);

                    // diff > umbral → este píxel es oscuro RESPECTO A SU ENTORNO → tinta
                    if (($l2 - $l1) > $INK_THRESHOLD) {
                        imagesetpixel($dest, $x, $y, $inkBlack);
                    }
                    // else → queda transparente (ya lleno con fullyTransparent)
                }
            }

            imagedestroy($src);
            imagedestroy($bgMap);

            // --- 7. trimImage en GD: escanear desde los 4 bordes y recortar ---
            // Equivalente exacto al Imagick::trimImage($fuzz)
            $left   = self::findEdge($dest, $width, $height, 'left');
            $right  = self::findEdge($dest, $width, $height, 'right');
            $top    = self::findEdge($dest, $width, $height, 'top');
            $bottom = self::findEdge($dest, $width, $height, 'bottom');

            if ($left !== null && $right !== null && $top !== null && $bottom !== null) {
                $cropW = $right  - $left  + 1;
                $cropH = $bottom - $top   + 1;

                $cropped = imagecreatetruecolor($cropW, $cropH);
                imagealphablending($cropped, false);
                imagesavealpha($cropped, true);
                $t2 = imagecolorallocatealpha($cropped, 0, 0, 0, 127);
                imagefill($cropped, 0, 0, $t2);

                imagecopy($cropped, $dest, 0, 0, $left, $top, $cropW, $cropH);
                imagedestroy($dest);
                $dest = $cropped;

                // --- 8. Padding 1:1 cuadrado con 95% de canvas utilization ---
                $cW = imagesx($dest);
                $cH = imagesy($dest);

                $maxSide    = max($cW, $cH);
                $targetSize = (int) ($maxSide / 0.95); // 5% de margen = 95% de la firma

                $pX = ($targetSize - $cW) / 2;
                $pY = ($targetSize - $cH) / 2;

                $padded = imagecreatetruecolor($targetSize, $targetSize);
                imagealphablending($padded, false);
                imagesavealpha($padded, true);
                $t3 = imagecolorallocatealpha($padded, 0, 0, 0, 127);
                imagefill($padded, 0, 0, $t3);

                imagecopy($padded, $dest, (int) $pX, (int) $pY, 0, 0, $cW, $cH);
                imagedestroy($dest);
                $dest = $padded;
            }

            // --- 9. Exportar como PNG con máxima calidad ---
            ob_start();
            imagepng($dest, null, 0); // Sin compresión → máxima nitidez
            $pngData = ob_get_clean();
            imagedestroy($dest);

            return $pngData;

        } finally {
            if (isset($deleteTmp) && $deleteTmp && isset($rutaTemp)) {
                @unlink($rutaTemp);
            }
        }
    }

    /**
     * Escanea la imagen desde un borde hacia adentro y devuelve la coordenada
     * del primer píxel NO completamente transparente.
     *
     * Equivalente a la lógica interna de Imagick::trimImage().
     */
    private static function findEdge(\GdImage $img, int $width, int $height, string $edge): ?int
    {
        switch ($edge) {
            case 'left':
                for ($x = 0; $x < $width; $x++) {
                    for ($y = 0; $y < $height; $y++) {
                        if (self::isInkPixel($img, $x, $y)) return $x;
                    }
                }
                break;
            case 'right':
                for ($x = $width - 1; $x >= 0; $x--) {
                    for ($y = 0; $y < $height; $y++) {
                        if (self::isInkPixel($img, $x, $y)) return $x;
                    }
                }
                break;
            case 'top':
                for ($y = 0; $y < $height; $y++) {
                    for ($x = 0; $x < $width; $x++) {
                        if (self::isInkPixel($img, $x, $y)) return $y;
                    }
                }
                break;
            case 'bottom':
                for ($y = $height - 1; $y >= 0; $y--) {
                    for ($x = 0; $x < $width; $x++) {
                        if (self::isInkPixel($img, $x, $y)) return $y;
                    }
                }
                break;
        }
        return null;
    }

    /**
     * Determina si un píxel tiene tinta (no es completamente transparente).
     */
    private static function isInkPixel(\GdImage $img, int $x, int $y): bool
    {
        $color = imagecolorat($img, $x, $y);
        $alpha = ($color >> 24) & 0x7F; // 127 = totalmente transparente, 0 = opaco
        return $alpha < 100; // Consideramos "tinta" si el canal alfa < 100
    }

    /**
     * Convierte una imagen a PNG optimizado para firma (ya está procesada en maikol_callate).
     */
    public static function optimizarParaFirma(string $pngData): string
    {
        return $pngData;
    }
}
