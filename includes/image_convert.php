<?php
// HEIC/HEIF → JPEG conversion helper.
// macOS: uses the bundled `sips` tool. Linux/shared hosts: falls back to
// ImageMagick `convert` then `magick`. Returns true when the JPEG exists and is non-empty.

function heic_convert_to_jpg(string $target, string $jpgPath): bool {
    if (file_exists($jpgPath)) { @unlink($jpgPath); }

    // 1) macOS native
    @shell_exec('/usr/bin/sips -s format jpeg ' . escapeshellarg($target) . ' --out ' . escapeshellarg($jpgPath) . ' 2>&1');
    if (file_exists($jpgPath) && filesize($jpgPath) > 0) return true;

    // 2) ImageMagick 6
    if (file_exists($jpgPath)) { @unlink($jpgPath); }
    @shell_exec('convert ' . escapeshellarg($target) . ' ' . escapeshellarg($jpgPath) . ' 2>&1');
    if (file_exists($jpgPath) && filesize($jpgPath) > 0) return true;

    // 3) ImageMagick 7
    if (file_exists($jpgPath)) { @unlink($jpgPath); }
    @shell_exec('magick ' . escapeshellarg($target) . ' ' . escapeshellarg($jpgPath) . ' 2>&1');
    return file_exists($jpgPath) && filesize($jpgPath) > 0;
}