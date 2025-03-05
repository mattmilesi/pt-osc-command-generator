<?php

namespace PtOscCommandGenerator;

use PHPUnit\Framework\TestCase;

class DsnOptionTest extends TestCase
{

    public function testIsValid()
    {
        $this->assertTrue(DsnOption::isValid(DsnOption::USER));
        $this->assertFalse(DsnOption::isValid('random-string'));
    }
}
