<?php
namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    #Get all products
    public function findAllProducts(): array
    {
        return $this->findAll();
    }

    # Get products by category ID
    public function findProductsByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->addSelect('c')
            ->where('c.id = :catId')
            ->setParameter('catId', $categoryId)
            ->getQuery()
            ->getResult();
    }

    # Get products with price greater than or equal to a minimum price
    public function findProductsByPrice(float $minPrice): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.price >= :price')
            ->setParameter('price', $minPrice)
            ->getQuery()
            ->getResult();
    }

    # Get all products with their associated category
    public function findProductsWithCategory(): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->addSelect('c')
            ->getQuery()
            ->getResult();
    }

    
}
