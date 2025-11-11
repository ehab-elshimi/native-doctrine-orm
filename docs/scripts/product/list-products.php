<?php
require_once __DIR__ . '/../config/bootstrap.php';

use App\Entity\Product;



# Using Query Builder To Get All Products With Categories   "Eager Loading"
$qb = $entityManager->createQueryBuilder();
$qb->select('p', 'c')
   ->from(Product::class, 'p')
   ->join('p.category', 'c');

$products = $qb->getQuery()->getResult();

foreach ($products as $product) {
    echo $product->getName() . " - " . $product->getPrice() . " - Category: " . $product->getCategory()->getName() . "\n";
}
