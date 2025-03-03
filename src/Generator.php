<?php

namespace PtOscCommandGenerator;

use PtOscCommandGenerator\Exceptions\ParserException;
use PhpMyAdmin\SqlParser\Components\AlterOperation;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Statements\AlterStatement;

class Generator
{
    private string $input;
    private Parser $parser;
    private array $commands = [];

    /**
     * @param string $input
     *
     * @throws ParserException
     */
    public function __construct(string $input)
    {
        $this->input = $input;
        $this->parse();
    }

    /**
     * @throws ParserException
     */
    protected function parse(): void
    {
        $this->parser = new Parser($this->input);

        $i = 1;
        foreach ($this->parser->statements as $statement) {
            if (!$statement instanceof AlterStatement) {
                throw new ParserException("No alter table query found in statement #{$i}");
            }
            if (empty($statement->altered)) {
                throw new ParserException("No alter operations found in statement #{$i}");
            }
            $table = $this->sanitizeTableName($statement->table);
            if (empty($table)) {
                throw new ParserException("Invalid table name in statement #{$i}");
            }
            $builder = new CommandBuilder();
            $builder->setTable($table);
            foreach ($statement->altered as $operation) {
                $builder->addOperation(AlterOperation::build($operation));
            }
            $this->commands[] = $builder->build();

            $i++;
        }
    }

    private function sanitizeTableName(string $table): string
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getParser(): Parser
    {
        return $this->parser;
    }
}
