<?php
declare(strict_types=1);

namespace Test;

use Faker\Factory;
use Faker\Generator;
use Helper\DbQuickUse;
use PDO;

/**
 * Class GenData
 * @package Test
 */
class GenData
{
    private DbQuickUse $query;
    private Generator $facker;

    /**
     * GenData constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->query = new DbQuickUse($pdo);
        $this->facker = Factory::create('fr_FR');
    }

}
