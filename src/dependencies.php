<?php
declare(strict_types=1);

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;

return static function (App $app): void {
    $c = $app->getContainer();

    $c[LoggerInterface::class] = static function (ContainerInterface $c): LoggerInterface {
        $config = $c->get('settings')['log'];
        $logger = new Logger($config['channel']);
        $logger->pushHandler(new RotatingFileHandler(
            'log', 10, Logger::DEBUG
        ));
        $logger
            ->pushProcessor(new UidProcessor())
            ->pushProcessor(new IntrospectionProcessor())
            ->pushProcessor(new WebProcessor());
        return $logger;
    };
};