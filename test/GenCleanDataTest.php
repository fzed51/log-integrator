<?php
declare(strict_types=1);

namespace Test;


/**
 * Class GenDataTest
 * @package Test
 */
class GenCleanDataTest extends DbTestCase
{
    /**
     * @return GenData
     */
    private function getGenerator():GenData
    {
        return new GenData($this->getPdo());
    }

}
