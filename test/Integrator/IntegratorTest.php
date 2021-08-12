<?php

namespace Integrator;

use PHPUnit\Framework\TestCase;

/**
 * test de IntegratorTest
 */
class IntegratorTest extends TestCase
{

    protected function getIntegrator(): Integrator
    {
        return new Integrator();
    }

    public function testProcess()
    {
        $this->expectNotToPerformAssertions();
        $i = $this->getIntegrator();
        $i->process();
    }
}
