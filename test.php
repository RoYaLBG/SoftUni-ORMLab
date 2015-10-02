<?php

ini_set('display_errors', 1);
require_once 'Autoloader.php';
\SoftUni\Autoloader::init();

\SoftUni\Core\Database::setInstance(
    \SoftUni\Config\DatabaseConfig::DB_INSTANCE,
    \SoftUni\Config\DatabaseConfig::DB_DRIVER,
    \SoftUni\Config\DatabaseConfig::DB_USER,
    \SoftUni\Config\DatabaseConfig::DB_PASS,
    \SoftUni\Config\DatabaseConfig::DB_NAME,
    \SoftUni\Config\DatabaseConfig::DB_HOST
);

$dbContext = new \SoftUni\EntityManager\DatabaseContext(
    \SoftUni\Repositories\BuildingsRepository::create(),
    \SoftUni\Repositories\UsersRepository::create()
);

$user = $dbContext->getUsersRepository()->filterById(8)->findOne();
$building = $dbContext->getBuildingsRepository()->filterById(1)->findOne();

var_dump($user);
var_dump($building);

$user->setUsername("Georgi");
$building->setName("Zlatotarsa4ka");

$dbContext->saveChanges();

