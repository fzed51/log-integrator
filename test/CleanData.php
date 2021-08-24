<?php
declare(strict_types=1);

namespace Test;

use Helper\DbQuickUse;
use PDO;

/**
 * Class CleanData
 * @package Test
 */
class CleanData
{
    private DbQuickUse $query;

    /**
     * GenData constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->query = new DbQuickUse($pdo);
    }

}
