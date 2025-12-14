<?php

$storageDir = __DIR__ . '/../storage/app/public/products';
$publicDir = __DIR__ . '/../public/storage/products';

echo "Files in storage/app/public/products:\n";
if (is_dir($storageDir)) {
    $files = array_diff(scandir($storageDir), ['.', '..']);
    foreach ($files as $file) {
        echo "  - $file\n";
    }
} else {
    echo "  Directory does not exist\n";
}

echo "\nFiles in public/storage/products:\n";
if (is_dir($publicDir)) {
    $files = array_diff(scandir($publicDir), ['.', '..']);
    foreach ($files as $file) {
        echo "  - $file\n";
    }
} else {
    echo "  Directory does not exist\n";
}
