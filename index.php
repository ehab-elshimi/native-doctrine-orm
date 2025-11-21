<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';


/* =======================================================================
 *  BEFORE USING THE CONTAINER (Manual Instantiation)
 *  -----------------------------------------------------------------------
 *  This section shows how controllers and services were manually created
 *  before introducing the Dependency Injection Container.
 *
 *  use App\Service\CategoryService;
 *  use App\Service\ProductService;
 *  use App\Controllers\CategoryController;
 *  use App\Controllers\ProductController;
 *
 *  // Get EntityManager instance
 *  $entityManager = require __DIR__ . '/config/bootstrap.php';
 *
 *  // Initialize Services
 *  $categoryService = new CategoryService($entityManager);
 *  $productService  = new ProductService($entityManager);
 *
 *  // Initialize Controllers
 *  $categoryController = new CategoryController($categoryService);
 *  $productController  = new ProductController($productService);
 * ======================================================================= */


/* =======================================================================
 *  AFTER USING THE CONTAINER (Automatic Dependency Injection)
 *  -----------------------------------------------------------------------
 *  The container now resolves all dependencies automatically. We simply
 *  request the controller we need, and the container handles the rest.
 * ======================================================================= */

// Get DI Container instance
$container = require __DIR__ . '/config/bootstrap.php';

// Retrieve controllers from the container
$categoryController = $container->get('categoryController');
$productController  = $container->get('productController');


// Retrieve the requested route, defaulting to "home"
$uri = $_GET['uri'] ?? 'home';


/* =======================================================================
 *  ROUTING SYSTEM
 *  -----------------------------------------------------------------------
 *  A simple router that maps URL parameters to controller actions.
 * ======================================================================= */

switch ($uri) {

    /* ---------------------------------------------------------------
     *  CATEGORY ROUTES
     * --------------------------------------------------------------- */

    case 'categories':
        // List all categories
        $categoryController->index();
        break;

    case 'category-detail':
        $name = $_GET['name'] ?? '';
        if ($name) {
            // Show a single category by name
            $categoryController->showByName($name);
        } else {
            echo "<h3>Please provide a category name</h3>";
        }
        break;

    case 'category-prices1':
        // Filter categories using JOIN (Query Builder)
        $categoryController->categoryPrices1();
        break;

    case 'category-prices2':
        // Filter categories using in-memory Collection filtering
        $categoryController->categoryPrices2();
        break;

    case 'category-prices3':
        // Filter categories using Doctrine Criteria API
        $categoryController->categoryPrices3();
        break;


    /* ---------------------------------------------------------------
     *  PRODUCT ROUTES
     * --------------------------------------------------------------- */

    case 'products':
        // List all products
        $productController->index();
        break;

    case 'products-by-category':
        // List products of a specific category
        $categoryId = (int)($_GET['category_id'] ?? 0);

        if ($categoryId) {
            $productController->showByCategory($categoryId);
        } else {
            echo "<h3>Please provide a valid category ID</h3>";
        }
        break;

    case 'products-by-price':
        // List products filtered by minimum price
        $minPrice = (float)($_GET['min'] ?? 0);

        $productController->showByPrice($minPrice);
        break;

    case 'products-with-category':
        // Show all products with their category information
        $productController->showWithCategory();
        break;

    case 'product-detail':
        // Show a single product by ID
        $productId = (int)($_GET['product_id'] ?? 0);

        if ($productId) {
            $productController->showDetail($productId);
        } else {
            echo "<h3>Please provide a valid product ID</h3>";
        }
        break;

    case 'cache-debug':
        // Display cache contents
        $categoryController->showCache();
        break;


    /* ---------------------------------------------------------------
     *  DEFAULT ROUTE (HOME PAGE)
     * --------------------------------------------------------------- */

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
                <li><a href='?uri=products-by-price&min=5000'>Products with Price ≥ 5000</a></li>

                <li><a href='?uri=category-prices1'>Category Prices — Solution 1 (Query Builder)</a></li>
                <li><a href='?uri=category-prices2'>Category Prices — Solution 2 (Filtered Collection)</a></li>
                <li><a href='?uri=category-prices3'>Category Prices — Solution 3 (Doctrine Criteria)</a></li>

                <li><a href='?uri=cache-debug'>Print Cache</a></li>
              </ul>";
        break;
}
