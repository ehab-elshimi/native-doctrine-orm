<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Controllers\CategoryController;
use App\Controllers\ProductController;
use App\Core\Container;
use App\Services\CategoryService;
use App\Services\ProductService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;

$container = new Container();

# EntityManager
$container->set('entityManager', function($c) {
    $connectionParams = [
        "dbname" => 'orm_database', // create database with name => 'orm_database'
        "user" => 'root',
        "password" => '', // password
        "host" => '127.0.0.1',
        "driver" => 'pdo_mysql'
    ];
    $conn = DriverManager::getConnection($connectionParams);

    $paths = [__DIR__ . '/../src/App/Entity'];
    $isDevMode = true;
    $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

    return new EntityManager($conn, $config);
});

# Services
$container->set('categoryService', function($c) {
    return new CategoryService($c->get('entityManager'));
});

$container->set('productService', function($c) {
    return new ProductService($c->get('entityManager'));
});

# Controllers
$container->set('categoryController', function($c) {
    return new CategoryController($c->get('categoryService'));
});

$container->set('productController', function($c) {
    return new ProductController($c->get('productService'));
});

return $container;
