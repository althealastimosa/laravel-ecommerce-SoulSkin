<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
$publicDir = __DIR__ . '/../public';
if (!file_exists($dbPath)) { echo "DB not found\n"; exit(1); }
$pdo = new PDO('sqlite:' . $dbPath);
$stmt = $pdo->query('SELECT id, name, image FROM products');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    $img = $r['image'] ?? null;
    $publicPath = $img ? $publicDir . '/' . ltrim($img, '/') : null;
    $exists = $publicPath ? (file_exists($publicPath) ? 'yes' : 'no') : 'no';
    $size = ($publicPath && file_exists($publicPath)) ? filesize($publicPath) : 0;
    echo "{$r['id']} | {$r['name']} | {$img} | exists: {$exists} | size: {$size}\n";
}
