<?php
declare(strict_types=1);

namespace Handlers\Dependencies;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LoggerInterface;
use Slim\Container;

return
    /**
     * @param \Slim\Container $container
     * @return \Slim\Container
     */
    static function (Container $container): Container {

        // Logger
        $container[LoggerInterface::class] = static function (Container $c): LoggerInterface {
            $settings = $c->get('settings');
            $logSettings = $settings['log'];
            $logger = new Logger($logSettings['channel']);
            $hLogger = new RotatingFileHandler($logSettings['directory'], (int)$logSettings['history'], Logger::DEBUG);
            $hLogger->setFormatter(new JsonFormatter());
            $logger->pushHandler($hLogger);
            $logger
                ->pushProcessor(new UidProcessor())
                ->pushProcessor(new IntrospectionProcessor())
                ->pushProcessor(new WebProcessor());
            return $logger;
        };
        $container['logger'] = static function (Container $c): LoggerInterface {
            return $c->get(LoggerInterface::class);
        };

        return $container;
    };
