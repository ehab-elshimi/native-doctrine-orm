<?php
namespace App\Services;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CategoryService
{
    /** ------------------------------------------------------------------
     *  Symfony Filesystem Cache Adapter
     * ------------------------------------------------------------------- */
    private FilesystemAdapter $cache;

    /** ------------------------------------------------------------------
     *  Entity Manager (Base Repository Access)
     * ------------------------------------------------------------------- */
    private EntityManagerInterface $entityManager;

    /** ------------------------------------------------------------------
     *  Category Repository (Encapsulation + DI Training)
     * ------------------------------------------------------------------- */
    private CategoryRepository $categoryRepo;

    /** ------------------------------------------------------------------
     *  Constructor — Dependency Injection
     *
     *  [-] Dependency Inversion (SOLID Principle #5):
     *      - This class depends on abstractions (Repository Interface /
     *        BaseRepository) instead of concrete implementations.
     *
     *  [-] Open/Closed Principle (SOLID Principle #2):
     *      - Repository logic can be extended without modifying this class.
     *
     *  [-] Polymorphism:
     *      - $categoryRepo may reference any repository implementing the
     *        required contract. Methods like findAllCategories() will behave
     *        the same externally while implemented differently internally.
     * ------------------------------------------------------------------- */
    public function __construct(EntityManagerInterface $entityManager)
    {
        // Assign Entity Manager
        $this->entityManager = $entityManager;

        // Inject Category Repository
        $this->categoryRepo = $this->entityManager->getRepository(Category::class);

        // Initialize Symfony Filesystem Cache
        $this->cache = new FilesystemAdapter(
            namespace: 'category_cache', // Cache namespace
            defaultLifetime: 3600        // 1 hour
        );
    }

    /* ================================================================
     *  Basic Methods
     * ================================================================ */

    public function getAllCategories(): array
    {
        return $this->categoryRepo->findAllCategories();
    }


    /* ================================================================
     *  Get All Categories (Cached)
     * ================================================================ */
    public function getAllCachedCategories(): array
    {
        $cacheKey = 'all_categories_arrays';

        $categories = $this->cache->get($cacheKey, function ($item) {
            $item->expiresAfter(3600);

            $categories = $this->categoryRepo->findAllCategories();

            // Format output
            $result = [];
            foreach ($categories as $category) {
                $result[] = [
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'products' => array_map(
                        fn($p) => [
                            'id' => $p->getId(),
                            'name' => $p->getName(),
                            'price' => $p->getPrice(),
                        ],
                        $category->getProducts()->toArray()
                    )
                ];
            }

            return $result;
        });

        return $categories;
    }


    /* ================================================================
     *  Debug — Show Cached Items
     * ================================================================ */
    public function debugCache(): void
    {
        echo "<h3>All Cached Keys:</h3>";

        $cacheItems = $this->cache->getItems(['all_categories']);

        foreach ($cacheItems as $key => $item) {
            echo "<b>Cache Key:</b> $key<br>";
            echo "<pre>" . print_r($item->get(), true) . "</pre><hr>";
        }
    }


    /* ================================================================
     *  Fetch Category by Name
     * ================================================================ */
    public function getCategoryByName(string $name): ?Category
    {
        return $this->categoryRepo->findCategoryByName($name);
    }


    /* ================================================================
     *  Fetch Categories with Their Products (Join)
     * ================================================================ */
    public function getCategoriesWithProducts(): array
    {
        return $this->categoryRepo->findCategoriesWithProducts();
    }


    /* ================================================================
     *  Solution 1 — Filtering Using JOIN Queries (Database Level)
     * ================================================================ */
    public function categoryPrices1(): void
    {
        $price = 18000;
        $total = 0;

        echo "<h4 style='color:green'> Minimum Price Filter: ($price) LE </h4>";

        $categories = $this->categoryRepo->categoryPrices1($price);

        foreach ($categories as $category) {
            $products = $category->getProducts();

            if ($products->isEmpty()) {
                echo "- No products above $price <br>";
            } else {
                echo "<h4 style='color:red'> Found (" . count($products) . ") Products in (" . $category->getName() . ") </h4>";
                echo "<ul>";
                foreach ($products as $product) {
                    $total++;
                    echo "<li>Product: " . $product->getName() . " | Price: " . $product->getPrice() . "</li>";
                }
                echo "</ul>";
            }

            echo "<hr>";
        }

        echo "<hr>Total Products: $total";
    }


    /* ================================================================
     *  Solution 2 — Filtering Loaded Collections (In-Memory Filtering)
     * ================================================================ */
    public function categoryPrices2(): void
    {
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


    /* ================================================================
     *  Solution 3 — Doctrine Criteria API (Advanced Filtering)
     * ================================================================ */
    public function categoryPrices3(int $minPrice = 8000): void
    {
        $categories = $this->categoryRepo->findAllCategories();

        foreach ($categories as $category) {
            $products = $category->getProducts();

            // Build criteria: price > minPrice, ordered DESC, limit 5
            $criteria = Criteria::create()
                ->where(Criteria::expr()->gt('price', $minPrice))
                ->orderBy(['price' => 'DESC'])
                ->setMaxResults(5);

            $filteredProducts = $products->matching($criteria);

            echo "<h3>Category: " . $category->getName() . "</h3>";

            if ($filteredProducts->isEmpty()) {
                echo "- No products above $minPrice<br>";
            } else {
                foreach ($filteredProducts as $product) {
                    echo "- Product: " . $product->getName() . " | Price: " . $product->getPrice() . "<br>";
                }
            }

            echo "<hr>";
        }
    }
}
