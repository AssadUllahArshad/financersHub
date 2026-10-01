<?php
// Keep source images intact; generate browser-selectable delivery sizes.
$root = dirname(__DIR__).'/public/assets/images';
$destination = $root.'/responsive';
if (!is_dir($destination)) mkdir($destination, 0755, true);
foreach (glob($root.'/editorial-*.webp') as $path) {
    $source = imagecreatefromwebp($path);
    foreach ([320, 640, 876] as $width) {
        $width = min($width, imagesx($source));
        $height = (int) round(imagesy($source) * $width / imagesx($source));
        $image = imagescale($source, $width, $height, IMG_BICUBIC);
        $output = $destination.'/'.pathinfo($path, PATHINFO_FILENAME).'-'.$width.'.webp';
        imagewebp($image, $output, 72);
        imagedestroy($image);
        echo basename($output).': '.filesize($output)." bytes\n";
    }
    imagedestroy($source);
}
