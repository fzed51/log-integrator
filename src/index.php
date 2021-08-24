<?php
declare(strict_types=1);

use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

/** @var callable $handleSettings */
$handleSettings = require './handleSettings.php';
$app = new App($handleSettings(require "../config/config.php"));


/** @var callable $handlerErrors */
$handlerErrors = require __DIR__ . '/handlerErrors.php';
$handlerErrors($app);

/** @var callable $handleDependencies */
$handleDependencies = require './handleDependencies.php';
$handleDependencies($app->getContainer());

/** @var callable $handlDependencies */
$handlMiddleware = require __DIR__ . '/handleMiddlewares.php';
$handlMiddleware($app);

/** @var callable $handlDependencies */
$handlRoute = require __DIR__ . '/routes.php';
$handlRoute($app);

$handleRoutes = require './handleRoutes.php';
