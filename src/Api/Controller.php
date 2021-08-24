<?php
declare(strict_types=1);

namespace Api;

use HttpException\BadRequestException;
use InstanceResolver\ResolverClass;
use JsonException;
use Psr\Log\LoggerInterface;
use ReflectionException;
use RuntimeException;
use Slim\Container;
use Slim\Exception\ContainerException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Controller
 * @package Api
 */
abstract class Controller
{
    protected Container $container;
    private ?ResolverClass $autowiring = null;
    private ?LoggerInterface $logger = null;

    /**
     * Controller constructor.
     * @param Container $container
     */
    final public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $needle
     * @return mixed
     */
    final protected function resolve(string $needle)
    {
        try {
            if ($this->autowiring === null) {
                $this->autowiring = $this->container->get(ResolverClass::class);
            }
            /** @var ResolverClass $autowiring */
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
                /** @var LoggerInterface $logger */
                $logger = $this->container->get(LoggerInterface::class);
                $this->logger = $logger;
            }
            return $this->logger;
        } catch (ContainerException $e) {
            throw new RuntimeException("Impossible de trouver le logger", $e->getCode(), $e);
        }
    }

    /**
     * @param Response $response
     * @param mixed $data
     * @return Response
     */
    protected function returnSuccess(Response $response, $data = null): Response
    {
        $formatter = new ResponseFormatter($response);
        return $formatter->formatSuccess($data);
    }

    /**
     * @param Response $response
     * @param string $newUrl
     * @param mixed $data
     * @return Response
     */
    protected function returnRedirect(Response $response, string $newUrl = "/", $data = null): Response
    {
        $formatter = new ResponseFormatter($response);
        return $formatter->formatRedirect($newUrl, $data);
    }

    /**
     * @param Request $request
     * @param null|callable $validator
     * @phpstan-param  (null|callable(mixed $data): void) $validator
     * @return mixed
     * @throws BadRequestException
     */
    protected function readBody(Request $request, ?callable $validator = null)
    {
        try {
            $data = json_decode((string)$request->getBody(), false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new BadRequestException("Le format du cops de la requète n'est pas valide.");
        }
        if (null !== $validator) {
            $validator($data);
        }
        return $data;
    }

    /**
     * @param Request $request
     * @param null|callable $validator
     * @return array<string,mixed>
     */
    protected function readParams(Request $request, ?callable $validator = null): array
    {
        /** @var array<string,mixed>|null $datas */
        $datas = $request->getParams();
        /** @var array<string,mixed> $datas */
        $datas ??= [];
        foreach ($datas as $key => $data) {
            if (is_string($data)) {
                $datas[$key] = urldecode($data);
            }
        }
        if (null !== $validator) {
            $validator($datas);
        }
        return $datas;
    }
}
