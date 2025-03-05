<?php

namespace PtOscCommandGenerator;

use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{

    public function testIsValid()
    {
        $this->assertTrue(Option::isValid(Option::ANALYZE_BEFORE_SWAP));
        $this->assertFalse(Option::isValid('random-string'));
    }
}
