<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private CategoryRepository $categoryRepo;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->categoryRepo = $entityManager->getRepository(Category::class);
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepo->findAllCategories();
    }

    public function getCategoryByName(string $name): ?Category
    {
        return $this->categoryRepo->findCategoryByName($name);
    }

    public function getCategoriesWithProducts(): array
    {
        return $this->categoryRepo->findCategoriesWithProducts();
    }

    #Join Solution
    public function categoryPrices1(): void
    {
        #Enter price = ????   
        $price = 18000;
        $total = 0;
        echo "<h4 style='color:green'> Products Min Price : ($price) LE  </h4>";
        $categories = $this->categoryRepo->categoryPrices1($price);
        foreach ($categories as $category) {
            $categoryName = $category->getName();
            $products = $category->getProducts();
            if ($products->isEmpty()) {
                echo "- No products above $price <br>";
            } else {
                echo "<h4 style='color:red'> Results : ( " .  count($products) . " ) Products In ( " . $categoryName . ") Category </h4>";
                echo "<ul>";
                foreach ($products as $product) {
                    $total++;
                    echo "<li>Product: " . $product->getName() . " | Price: " . $product->getPrice() . "</li>";
                }
                echo "</ul>";
            }

            echo "<hr>";
        }
        echo "<hr>";
        echo "Total Products : $total";
    }

    # Filter Products Collection Solution
    public function categoryPrices2(): void
    {
        #Enter price = ????   
        $price = 500;
        $filteredCategories = $this->categoryRepo->categoryPrices2($price);

        echo "<div class='categories'>";

        foreach ($filteredCategories as $category) {
            echo "<div class='category'>";
            echo "<h2>Category: " . $category->getName() . "</h2>";

            $products = $category->getProducts();

            if ($products->isEmpty()) {
                echo "<p>- No products above $price</p>";
            } else {
                echo "<ul>";
                foreach ($products as $product) {
                    echo "<li>Product: " . $product->getName() . " | Price: " . $product->getPrice() . "</li>";
                }
                echo "</ul>";
            }

            echo "</div>";
        }

        echo "</div>";
    }
    # Third Solution Using Criteria
    public function categoryPrices3(int $minPrice = 100)
    {
        $minPrice  = 8000;
        # get all categories
        $categories = $this->categoryRepo->findAllCategories();

        foreach ($categories as $category) {

            # get products collection for this category
            $products = $category->getProducts();

            # create criteria to filter products by price, order desc, limit 5
            $criteria = Criteria::create()
                ->where(Criteria::expr()->gt('price', $minPrice))
                ->orderBy(['price' => 'DESC'])
                ->setMaxResults(5);

            # apply criteria
            $filteredProducts = $products->matching($criteria);

            # display category and filtered products
            echo "<h3>Category: " . $category->getName() . "</h3>";
            if ($filteredProducts->isEmpty()) {
                echo "- No products above " . $minPrice . "<br>";
            } else {
                foreach ($filteredProducts as $product) {
                    echo "- Product: " . $product->getName() . " | Price: " . $product->getPrice() . "<br>";
                }
            }
            echo "<hr>";
        }
    }
}
