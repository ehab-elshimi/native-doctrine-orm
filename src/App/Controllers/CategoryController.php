<?php

namespace App\Controllers;

use App\Services\CategoryService;

class CategoryController
{
    # CategoryService instance (Dependency Injection)
    private CategoryService $categoryService;

    # Constructor receives the service
    public function __construct(CategoryService $categoryService)
    {

        $this->categoryService = $categoryService;
    }

    # Display all categories
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();

        echo "<h3>All Categories:</h3><ul>";
        foreach ($categories as $cat) {
            echo "<li>
                    <a href='?uri=products-by-category&category_id={$cat->getId()}'>
                        {$cat->getName()}
                    </a>
                </li>";
        }
        echo "</ul>";
    }

    # Display categories with their products
    public function showWithProducts(): void
    {
        $categories = $this->categoryService->getCategoriesWithProducts();

        echo "<h3>Categories with Products:</h3>";
        foreach ($categories as $cat) {
            echo "<b>{$cat->getName()}</b> (" . count($cat->getProducts()) . " products)<br>";
        }
    }

    # Display a single category by name
    public function showByName(string $name): void
    {
        $category = $this->categoryService->getCategoryByName($name);

        if ($category) {
            echo "<h3>Category: {$category->getName()}</h3>";
            echo "Created at: " . $category->getCreatedAt()->format('Y-m-d H:i:s');
        } else {
            echo "<h3>Category not found</h3>";
        }
    }

    public function categoryPrices1()
    {
        return $this->categoryService->categoryPrices1();
    }
    public function categoryPrices2()
    {
        return $this->categoryService->categoryPrices2();
    }
    public function categoryPrices3()
    {
        return $this->categoryService->categoryPrices3();
    }
    # In CategoryController
    public function showCache(): void
    {
        $this->categoryService->getAllCachedCategories();
        $this->categoryService->debugCache();
    }
}
