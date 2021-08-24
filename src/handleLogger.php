<?php
declare(strict_types=1);

namespace handleLogger;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;

$config = require __DIR__ . '/../config/config.php';
$configLog = $config['log'];
$logger = new Logger($configLog['channel']);
$hLogger = new RotatingFileHandler('./logs/at', 10, Logger::DEBUG);
$hLogger->setFormatter(new JsonFormatter());
$logger->pushHandler($hLogger);
$logger
    ->pushProcessor(new UidProcessor())
    ->pushProcessor(new IntrospectionProcessor())
    ->pushProcessor(new WebProcessor());
return $logger;
