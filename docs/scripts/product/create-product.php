<?php
require_once __DIR__ . '/../config/bootstrap.php';

use App\Entity\Product;
use App\Entity\Category;

# At First Select The Category
$categoryRepo = $entityManager->getRepository(Category::class);
$category = $categoryRepo->findOneBy(['name' => 'Electronics']);

# Create Product
$product = new Product();
$product->setName('Laptop')
        ->setPrice(1500)
        ->setCategory($category);

# Save Product
$entityManager->persist($product);
$entityManager->flush();

echo "Product created with ID " . $product->getId() . "\n";

