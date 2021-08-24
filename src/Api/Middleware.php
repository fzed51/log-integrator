<?php
declare(strict_types=1);

namespace Api;

use InstanceResolver\ResolverClass;
use Psr\Log\LoggerInterface;
use ReflectionException;
use RuntimeException;
use Slim\Container;
use Slim\Exception\ContainerException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Middleware
 * @package Api
 */
abstract class Middleware
{
    protected Container $container;
    private ?ResolverClass $autowiring = null;
    private ?LoggerInterface $logger = null;

    /**
     * Middleware constructor.
     * @param Container $container
     */
    final public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @phpstan-param callable(Request $request, Response $response): Response $next
     * @return Response
     */
    abstract public function __invoke(Request $request, Response $response, callable $next): Response;

    /**
     * @param string $needle
     * @return mixed
     */
    final protected function resolve(string $needle)
    {
        try {
            if ($this->autowiring === null) {
                /** @var ResolverClass autowiring */
                $autowiring = $this->container->get(ResolverClass::class);
                $this->autowiring = $autowiring;
            }
            $autowiring = $this->autowiring;
            return $autowiring($needle);
        } catch (ReflectionException | ContainerException $e) {
            throw new RuntimeException("Impossible de résoudre " . $needle, $e->getCode(), $e);
        }
    }

    /**
     * @return LoggerInterface
     */
    final protected function log(): LoggerInterface
    {
        try {
            if ($this->logger === null) {
                /** @var LoggerInterface autowiring */
                $logger = $this->container->get(LoggerInterface::class);
                $this->logger = $logger;
            }
            return $this->logger;
        } catch (ContainerException $e) {
            throw new RuntimeException("Impossible de trouver le logger", $e->getCode(), $e);
        }
    }
}
