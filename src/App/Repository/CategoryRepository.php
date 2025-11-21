<?php
namespace App\Repository;

use App\Entity\Category;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

# Repository for Category entity Handles all database queries related to categories
class CategoryRepository extends EntityRepository
{
    public function findAllCategories(): array
    {
        # Use Category Repository Find All Method To Get All Category Class "instances" From Memory 
        return $this->findAll();
    }

    # find category by name
    public function findCategoryByName(string $name): ?Category
    {
        return $this->findOneBy(['name' => $name]);
    }
  
    # I will Get Category - Products By Product Price Join solution
    public function categoryPrices1(int $price = 100): array
    {
        return $this->createQueryBuilder('category')
            ->innerJoin('category.products', 'products', 'WITH', 'products.price > :minPrice')
            ->addSelect('products')
            ->setParameter('minPrice', $price)
            ->orderBy('products.price', 'DESC')
            ->getQuery()
            ->getResult();
    }

    # Another Solution 
    public function categoryPrices2(int $price = 100): array
    {
        # this returns array of categories objects
        $categories = $this->findAllCategories();

        foreach ($categories as $category) {
            # Get ALL Related Products
            $products = $category->getProducts();
            # Filter them
            $filtered = $products->filter(function($product) use ($price) {
                return $product->getPrice() > $price;
            });
            # clear Array Collection in memory 
            $products->clear();
            # Fill it with Filtered Items
            foreach ($filtered as $p) {
                $products->add($p);
            }
        }
        return $categories;
    }
   
}
