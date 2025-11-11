<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;


#------------------------------------------- DBAL Layer -----------------------------------------------------------------
# my-connection-data
$connectionParams = [
    "dbname" => 'orm_database',
    "user" => 'root',
    "password" => 'Ehab@2030',
    "host" => '127.0.0.1',
    "driver" => 'pdo_mysql'
];
# Use Driver Of DBAL TO make connection but my configuration will be through Orm => it will make it
$conn = DriverManager::getConnection($connectionParams);

#------------------------------------------- ORM Layer -----------------------------------------------------------------
# All My Paths e-specially Entities Path So It Can Annotated it and Start His Powerful Jobs
$paths = [__DIR__ . '/../src/Entity'];
$isDevMode = true;

# Set My Doctrine ORM configuration
$ormConfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

# Connect and Make Instance Of Entity Manager => GO with $entityManager or $em
return new EntityManager($conn, $ormConfig);

