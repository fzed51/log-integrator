<?php
declare(strict_types=1);

namespace Api;

use PDO;
use Psr\Log\LoggerInterface;

/**
 * Class QueryPdo
 * @package Api
 */
abstract class QueryPdo extends Query
{
    use PdoQueryable;

    /**
     * QueryPdo constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param PDO $pdo
     */
    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
        parent::__construct($logger);
        $this->setPdo($pdo);
    }
}
