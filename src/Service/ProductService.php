<?php
namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private ProductRepository $productRepo;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->productRepo = $entityManager->getRepository(Product::class);
    }

    public function getAllProducts(): array
    {
        return $this->productRepo->findAllProducts();
    }

    public function getProductsByCategory(int $categoryId): array
    {
        return $this->productRepo->findProductsByCategory($categoryId);
    }

    public function getProductsByPrice(float $minPrice): array
    {
        return $this->productRepo->findProductsByPrice($minPrice);
    }

    public function getProductsWithCategory(): array
    {
        return $this->productRepo->findProductsWithCategory();
    }
}
