<?php
/** @noinspection PhpMissingParentConstructorInspection */
declare(strict_types=1);

namespace Test;

use InstanceResolver\ResolverClass;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use PDO;
use Psr\Log\LoggerInterface;
use ReflectionException;
use RuntimeException;
use Slim\App;
use Slim\Container as SlimContainer;

/**
 * Class AppStub
 * @package Test
 */
class AppStub extends App
{
    private SlimContainer $container;

    /**
     * AppStub constructor.
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings)
    {

        $this->container = new SlimContainer($settings);
    }

    /**
     * @return SlimContainer
     */
    public function getContainer(): SlimContainer
    {
        return $this->container;
    }
}

/**
 * Class TestCase de base pour les tests du projet
 */
class ActionTestCase extends DbTestCase
{
    private ?SlimContainer $container = null;
    private ?ResolverClass $resolver = null;
    private ?loggerInterface $logger = null;

    /**
     * @param string $className
     * @return mixed
     */
    protected function resolve(string $className)
    {
        if ($this->resolver === null) {
            $this->resolver = new ResolverClass($this->getContainer());
        }
        $resolver = $this->resolver;
        try {
            return $resolver($className);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return SlimContainer
     */
    protected function getContainer(): SlimContainer
    {
        if ($this->container === null) {
            $app = new AppStub([]);
            $handlDependencies = require __DIR__ . '/../src/dependencies.php';
            $handlDependencies($app);
            $this->container = $app->getContainer();
            $self = $this;
            $this->container[PDO::class] = static function () use ($self) {
                return $self->getPdo();
            };
            $this->container[LoggerInterface::class] = static function () use ($self) {
                return $self->getLogger();
            };
            $this->container["logger"] = static function () use ($self) {
                return $self->getLogger();
            };
        }
        return $this->container;
    }

    protected function getLogger(): loggerInterface
    {
        if ($this->logger === null) {
            $logger = new Logger("test");
            $logger->pushProcessor(new IntrospectionProcessor());
            $logger->pushHandler(new StreamHandler(__DIR__ . "/test.log", Logger::DEBUG));
            $this->logger = $logger;
        }
        return $this->logger;
    }

    /**
     * @return GenData
     */
    protected function getGenerator(): GenData
    {
        return new GenData($this->getPdo());
    }

    protected function getCleanner(): CleanData
    {
        return new CleanData($this->getPdo());
    }
}
