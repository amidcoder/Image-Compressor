<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$quality     = (int)($_POST['quality'] ?? 80);
$convertWebp = ($_POST['convert_webp'] ?? '') === 'yes';
$keepExif    = ($_POST['keep_exif'] ?? '') === 'yes';

$uploadDir = __DIR__ . '/uploads/';
$outputDir = __DIR__ . '/output/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

if (!isset($_FILES['images'])) die("No files uploaded.");

$files = $_FILES['images'];
$total = count($files['name']);

echo "<h2 class='font-bold text-lg mb-3'>Processed Files:</h2>";

for ($i = 0; $i < $total; $i++) {

    $tmp  = $files['tmp_name'][$i];
    $name = time() . "_" . basename($files['name'][$i]);
    $inputPath = $uploadDir . $name;

    move_uploaded_file($tmp, $inputPath);

    $info = pathinfo($inputPath);
    $ext  = strtolower($info['extension']);
    $outputExt = $convertWebp ? 'webp' : $ext;
    $outputPath = $outputDir . $info['filename'] . "_compressed." . $outputExt;

    if (extension_loaded('imagick')) {

        $image = new Imagick($inputPath);

        if ($convertWebp) {
            $image->setImageFormat('webp');
        }

        $image->setImageCompressionQuality($quality);

        if (!$keepExif) {
            $image->stripImage();
        }

        $image->writeImage($outputPath);
        $image->clear();
        $image->destroy();

    } elseif (extension_loaded('gd')) {

        $mime = mime_content_type($inputPath);

        switch ($mime) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($inputPath);
                break;

            case 'image/png':
                $img = imagecreatefrompng($inputPath);
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
                break;

            default:
                echo "<p>Unsupported file: $name</p>";
                continue 2;
        }

        if ($convertWebp) {
            imagewebp($img, $outputPath, $quality);
        } else {
            if ($mime === 'image/jpeg') {
                imagejpeg($img, $outputPath, $quality);
            } elseif ($mime === 'image/png') {
                $pngLevel = round((100 - $quality) / 10);
                imagepng($img, $outputPath, $pngLevel);
            }
        }

        imagedestroy($img);
    }

    echo "<p><a class='text-blue-600 underline' href='output/" . basename($outputPath) . "' target='_blank'>" 
        . basename($outputPath) . "</a></p>";
}
