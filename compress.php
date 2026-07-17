<?php
$src_dirs = [
    __DIR__ . '/storage/app/public/events',
    __DIR__ . '/storage/app/public/profiles'
];
$dest_dir = __DIR__ . '/optimized';

if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);
if (!is_dir($dest_dir.'/events')) mkdir($dest_dir.'/events', 0755, true);
if (!is_dir($dest_dir.'/profiles')) mkdir($dest_dir.'/profiles', 0755, true);

foreach ($src_dirs as $dir) {
    if(!is_dir($dir)) continue;
    $type = basename($dir);
    $files = glob($dir . '/*.*');
    foreach ($files as $file) {
        $filename = basename($file);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $dest = $dest_dir . '/' . $type . '/' . $filename;
        
        list($width, $height) = @getimagesize($file);
        if (!$width) {
            copy($file, $dest);
            continue;
        }
        
        $new_width = 800;
        $new_height = ($height / $width) * $new_width;
        
        $thumb = imagecreatetruecolor($new_width, $new_height);
        
        if ($ext == 'jpg' || $ext == 'jpeg') {
            $source = imagecreatefromjpeg($file);
        } elseif ($ext == 'png') {
            $source = imagecreatefrompng($file);
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        } else {
            copy($file, $dest);
            continue;
        }
        
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        
        if ($ext == 'jpg' || $ext == 'jpeg') {
            imagejpeg($thumb, $dest, 75);
        } elseif ($ext == 'png') {
            imagepng($thumb, $dest, 7);
        }
        imagedestroy($thumb);
        imagedestroy($source);
    }
}

$zip = new ZipArchive();
if ($zip->open(__DIR__.'/optimized_images.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $files = glob($dest_dir . '/events/*.*');
    foreach ($files as $file) {
        $zip->addFile($file, 'events/' . basename($file));
    }
    $files = glob($dest_dir . '/profiles/*.*');
    foreach ($files as $file) {
        $zip->addFile($file, 'profiles/' . basename($file));
    }
    $zip->close();
    echo "Done! Optimized Zip Size: " . round(filesize(__DIR__.'/optimized_images.zip') / 1024 / 1024, 2) . " MB\n";
} else {
    echo "Failed to create zip";
}
?>
