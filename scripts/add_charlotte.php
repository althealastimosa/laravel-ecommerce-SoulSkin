<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) {
    echo "Database file not found: $dbPath\n";
    exit(1);
}
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if a product with that name exists
    $name = 'Charlotte Tilbury';
    $stmt = $pdo->prepare('SELECT id FROM products WHERE name = :name');
    $stmt->execute([':name' => $name]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($exists) {
        echo "Product already exists with id: " . $exists['id'] . "\n";
        exit(0);
    }

    $image = '/storage/products/1765649800_charlotte.jpg';
    $description = 'Placeholder product for Charlotte Tilbury.';
    $price = 1500.00;
    $stock = 10;
    $now = date('Y-m-d H:i:s');

    $insert = $pdo->prepare('INSERT INTO products (name, description, price, stock, image, created_at, updated_at) VALUES (:name, :description, :price, :stock, :image, :now, :now)');
    $insert->execute([
        ':name' => $name,
        ':description' => $description,
        ':price' => $price,
        ':stock' => $stock,
        ':image' => $image,
        ':now' => $now
    ]);

    $id = $pdo->lastInsertId();
    echo "Inserted product id: $id\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
