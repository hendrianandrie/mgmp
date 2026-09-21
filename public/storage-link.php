<?php

$target = __DIR__ . '/../storage/app/public';
$shortcut = __DIR__ . '/storage';

if (file_exists($shortcut)) {
    echo "Tautan 'storage' sudah ada di folder public.";
} else {
    if (@symlink($target, $shortcut)) {
        echo "Berhasil! Tautan storage berhasil dibuat.";
    } else {
        echo "Gagal membuat symlink otomatis via PHP symlink().";
    }
}
