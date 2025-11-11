<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Service\CategoryService;
use App\Service\ProductService;
use App\Controllers\CategoryController;
use App\Controllers\ProductController;

# Get Instance of EntityManager
$entityManager = require __DIR__ . '/config/bootstrap.php';


# Initialize Services
$categoryService = new CategoryService($entityManager);
$productService  = new ProductService($entityManager);

# Initialize Controllers
$categoryController = new CategoryController($categoryService);
$productController  = new ProductController($productService);

# Get current route
$uri = $_GET['uri'] ?? 'home';

switch ($uri) {
    # -------------------------------- CATEGORY ROUTES -------------------------------------------------------
    case 'categories':
        #List all categories with links
        $categoryController->index(); 
        break;

    case 'category-detail':
        $name = $_GET['name'] ?? '';
        if ($name) {
            #Show a single category
            $categoryController->showByName($name); 
        } else {
            echo "<h3>Please provide a category name</h3>";
        }
        break;

    case 'category-prices1':
        # List all categories with links
        $categoryController->categoryPrices1();  
        break;

    case 'category-prices2':
            # List all categories with links
            $categoryController->categoryPrices2();  
            break;
            
    case 'category-prices3':
            # List all categories with links
            $categoryController->categoryPrices3();  
            break;

   # -------------------------- PRODUCT ROUTES -----------------------------------
    case 'products':
        # List all products with links
        $productController->index(); 
        break;

    case 'products-by-category':
        # Products in a category
        $categoryId = (int)($_GET['category_id'] ?? 0);
        if ($categoryId) {
            $productController->showByCategory($categoryId); 
        } else {
            echo "<h3>Please provide a valid category ID</h3>";
        }
        break;

    case 'products-by-price':
        #Products >= min price
        $minPrice = (float)($_GET['min'] ?? 0);
        $productController->showByPrice($minPrice);  
        break;

    case 'products-with-category':
        $productController->showWithCategory();
        break;

    case 'product-detail':
        # Show single product
        $productId = (int)($_GET['product_id'] ?? 0);
        if ($productId) {
            $productController->showDetail($productId); 
        } else {
            echo "<h3>Please provide a valid product ID</h3>";
        }
        break;

    # ------------------------------- DEFAULT ROUTE -------------------------------------------
    default:
        echo "<h2>Welcome to the Doctrine Demo App!</h2>";
        echo "<h3>Categories</h3>
              <ul>
                <li><a href='?uri=categories'>All Categories</a></li>
              </ul>";
        echo "<h3>Products</h3>
              <ul>
                <li><a href='?uri=products'>All Products</a></li>
                <li><a href='?uri=products-with-category'>Products with Categories</a></li>
                <li><a href='?uri=products-by-price&min=5000'>Products Price >= 5000</a></li>
                <li><a href='?uri=category-prices1'>Category By Price Solution 1 Query Builder</a></li>
                <li><a href='?uri=category-prices2'>Category By Price Solution 2 Filtered Collection</a></li>
                <li><a href='?uri=category-prices3'>Category By Price Solution 3 Using Doctrine Criteria</a></li>
              </ul>";
        break;
}
