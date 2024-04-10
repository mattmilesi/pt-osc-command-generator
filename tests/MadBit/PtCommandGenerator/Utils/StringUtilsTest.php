<?php

namespace MadBit\PtCommandGenerator\Utils;

use PHPUnit\Framework\TestCase;

class StringUtilsTest extends TestCase
{
    public function testEncodeDoubleQuotedArgument()
    {
        $this->assertEquals('foo', StringUtils::encodeDoubleQuotedArgument('foo'));
        $this->assertEquals('foo bar', StringUtils::encodeDoubleQuotedArgument('foo bar'));
        $this->assertEquals('foo\\"bar', StringUtils::encodeDoubleQuotedArgument('foo"bar'));
        $this->assertEquals('foo\\`bar', StringUtils::encodeDoubleQuotedArgument('foo`bar'));
        $this->assertEquals('foo\\!bar', StringUtils::encodeDoubleQuotedArgument('foo!bar'));
        $this->assertEquals('foo\\$bar', StringUtils::encodeDoubleQuotedArgument('foo$bar'));
        $this->assertEquals('foobar', StringUtils::encodeDoubleQuotedArgument("foo\nbar"));
        $this->assertEquals('foobar', StringUtils::encodeDoubleQuotedArgument("foo\rbar"));
        $this->assertEquals('foobar', StringUtils::encodeDoubleQuotedArgument("foo\tbar"));
    }
}
