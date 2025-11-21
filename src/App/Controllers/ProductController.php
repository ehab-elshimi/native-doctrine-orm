<?php
namespace App\Controllers;

use App\Services\ProductService;

class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): void
    {
        $products = $this->productService->getAllProducts();

        echo "<h3>All Products:</h3><ul>";
        foreach ($products as $product) {
            echo "<li>
                    <a href='?uri=product-detail&product_id={$product->getId()}'>
                        {$product->getName()} - {$product->getPrice()} USD
                    </a>
                  </li>";
        }
        echo "</ul>";
    }

    public function showByCategory(int $categoryId): void
    {
        $products = $this->productService->getProductsByCategory($categoryId);

        if (!$products) {
            echo "<h3>No products found for this category.</h3>";
            return;
        }

        echo "<h3>Products in Category ID {$categoryId}:</h3><ul>";
        foreach ($products as $product) {
            echo "<li>
                    <a href='?uri=product-detail&product_id={$product->getId()}'>
                        {$product->getName()} - {$product->getPrice()} USD
                    </a>
                  </li>";
        }
        echo "</ul>";
    }

    public function showByPrice(float $minPrice): void
    {
        $products = $this->productService->getProductsByPrice($minPrice);

        if (!$products) {
            echo "<h3>No products found with price >= {$minPrice} USD.</h3>";
            return;
        }

        echo "<h3>Products with Price >= {$minPrice} USD:</h3><ul>";
        foreach ($products as $product) {
            echo "<li>
                    <a href='?uri=product-detail&product_id={$product->getId()}'>
                        {$product->getName()} - {$product->getPrice()} USD
                    </a>
                  </li>";
        }
        echo "</ul>";
    }

    public function showWithCategory(): void
    {
        $products = $this->productService->getProductsWithCategory();

        echo "<h3>Products with Categories:</h3><ul>";
        foreach ($products as $product) {
            echo "<li>
                    <a href='?uri=product-detail&product_id={$product->getId()}'>
                        {$product->getName()} - {$product->getPrice()} USD
                    </a>
                    (Category: <a href='?uri=products-by-category&category_id={$product->getCategory()->getId()}'>{$product->getCategory()->getName()}</a>)
                  </li>";
        }
        echo "</ul>";
    }

    public function showDetail(int $productId): void
    {
        $products = $this->productService->getAllProducts();
        $product = null;

        foreach ($products as $p) {
            if ($p->getId() === $productId) {
                $product = $p;
                break;
            }
        }

        if (!$product) {
            echo "<h3>Product not found</h3>";
            return;
        }

        echo "<h3>Product Details:</h3>";
        echo "<p>Name: {$product->getName()}</p>";
        echo "<p>Price: {$product->getPrice()} USD</p>";
        echo "<p>Category: <a href='?uri=products-by-category&category_id={$product->getCategory()->getId()}'>{$product->getCategory()->getName()}</a></p>";
    }
}
