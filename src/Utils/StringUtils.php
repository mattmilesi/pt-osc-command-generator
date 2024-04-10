<?php

namespace MadBit\PtCommandGenerator\Utils;

class StringUtils
{
    public static function encodeDoubleQuotedArgument(string $argument): string
    {
        $transformations = [
            '"' => '\"',
            '`' => '\`',
            '!' => '\!',
            '$' => '\$',
            "\n" => '',
            "\r" => '',
            "\t" => '',
        ];

        return str_replace(
            array_keys($transformations),
            array_values($transformations),
            $argument
        );
    }
}