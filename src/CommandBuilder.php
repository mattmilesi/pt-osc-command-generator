<?php

namespace MadBit\PtCommandGenerator;

class CommandBuilder
{
    private string $table;
    private array $operations = [];
    private bool $dryRun = true;

    public function setTable(string $table): CommandBuilder
    {
        $this->table = $table;

        return $this;
    }

    public function addOperation(string $operation): CommandBuilder
    {
        $this->operations[] = $operation;

        return $this;
    }

    public function build(): string
    {
        $action = $this->dryRun ? 'dry-run' : 'execute';
        $table = $this->table;
        $operations = $this->encodeDoubleQuotedArgument(implode(', ', $this->operations));

        $options = [
            $action => null,
            'recursion-method' => 'none',
            'progress' => 'percentage,1',
            'no-drop-old-table' => null,
            'alter-foreign-keys-method' => 'auto',
            'alter' => '"' . $operations . '"',
        ];
        $optionsString = implode(' ', array_map(function ($key, $value) {
            return "--{$key}" . ($value ? " {$value}" : '');
        }, array_keys($options), $options));

        return 'pt-online-schema-change ' . $optionsString . ' h=$HOST,D=$DBNAME,t=' . $table . ',u=$DBUSERNAME,p=$DBPWD';
    }

    private function encodeDoubleQuotedArgument(string $argument): string
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
