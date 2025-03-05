<?php


namespace PtOscCommandGenerator;

use PHPUnit\Framework\TestCase;

class StatementParserTest extends TestCase
{
    public function testGetCommands()
    {
        $parser = new StatementParser('ALTER TABLE `users` CHANGE `first_name` `first_name` VARCHAR(100)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NOT NULL;');
        $commands = $parser->getCommands();
        $firstCommand = $commands[0];

        $this->assertCount(1, $commands);
        $this->assertEquals([
            'CHANGE `first_name` `first_name` VARCHAR(100)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NOT NULL',
        ], $firstCommand->getOperations());
        $this->assertEquals('users', $firstCommand->getDsnOption(DsnOption::TABLE));
    }
}
