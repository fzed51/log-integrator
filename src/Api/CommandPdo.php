<?php
declare(strict_types=1);

namespace Api;

use PDO;
use Psr\Log\LoggerInterface;

/**
 * Class CommandPdo
 * @package Api
 */
abstract class CommandPdo extends Command
{
    use PdoQueryable;

    /**
     * CommandPdo constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param PDO $pdo
     */
    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
        parent::__construct($logger);
        $this->setPdo($pdo);
    }

    /**
     * retourne la dernière clé primaire
     * @param string $table
     * @param string $pk
     * @return int
     */
    protected function getLLastPk(string $table, string $pk): int
    {
        $sql = "SELECT max($pk) FROM $table";
        $this->setReqSql($sql);
        $pk = $this->execute([])->fetchColumn();
        return (int)$pk;
    }
}
