<?php
require_once __DIR__ . '/../config/bootstrap.php';

use App\Entity\Product;

# At First Select The Product
$productRepo = $entityManager->getRepository(Product::class);
$product = $productRepo->findOneBy(['name' => 'Laptop']);
# Then Update Price 
if ($product) {
    $product->setPrice(1600);
    $entityManager->flush();
    echo "Product updated successfully.\n";
} else {
    echo "Product not found.\n";
}
