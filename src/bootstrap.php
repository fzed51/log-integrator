<?php
declare(strict_types=1);

use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = [
    "settings" => require __DIR__ . '/../config/config.php'
];
$app = new App($settings);

// Set up dependencies
$hDependencies = require __DIR__ . '/dependencies.php';
$hDependencies($app);

// Register middleware
$hMiddleware = require __DIR__ . '/middleware.php';
$hMiddleware($app);

// Register routes
$hRoute = require __DIR__ . '/routes.php';
$hRoute($app);

// Run!
$app->run();