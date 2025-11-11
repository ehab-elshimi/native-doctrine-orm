<?php

$entityManager = require __DIR__ . '/../config/bootstrap.php';

use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\Category;
use App\Entity\Product;

$schemaTool = new SchemaTool($entityManager);
$schemaTool->createSchema([
    $entityManager->getClassMetadata(Category::class),
    $entityManager->getClassMetadata(Product::class),
]);

echo "Database schema created successfully.\n";
