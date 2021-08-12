<?php
declare(strict_types=1);

namespace Integrator;

class Integrator
{
    /** @var \Monolog\Logger */
    private $log;

    public function __construct()
    {
        $this->log = require __DIR__ . "/../handleLogger.php";
    }

    public function process()
    {
        $this->log->info("run " . __METHOD__);
        $logs = new LogDirectory('./logs');
    }
}