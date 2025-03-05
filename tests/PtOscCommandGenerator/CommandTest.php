<?php

namespace PtOscCommandGenerator;

use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testSetTable(): void
    {
        $builder = new Command();
        $builder->setTable('customers');
        $this->assertEquals('customers', $builder->getTable());
    }

    public function testAddOperation(): void
    {
        $builder = new Command();
        $builder->addOperation('ADD column a INT');
        $builder->addOperation('ADD column b VARCHAR(255)');
        $this->assertCount(2, $builder->getOperations());
    }

    public function testToString(): void
    {
        $command = (new Command())
            ->setHost('$HOST')
            ->setDatabase('$DATABASE')
            ->setTable('customers')
            ->setUser('$USER')
            ->setPassword('$PASSWORD')
            ->addOperation('ADD column a INT')
            ->addOperation('ADD column b VARCHAR(255)')
            ->setDryRunMode();

        $this->assertEquals(
            'pt-online-schema-change --dry-run --alter "ADD column a INT, ADD column b VARCHAR(255)" h=$HOST,D=$DATABASE,t=customers,u=$USER,p=********',
            $command->toString()
        );
    }

    public function testToStringWithExecuteAndOptions(): void
    {
        $command = (new Command())
            ->setHost('$HOST')
            ->setDatabase('$DATABASE')
            ->setTable('customers')
            ->setUser('$USER')
            ->setPassword('$PASSWORD')
            ->addOperation('ADD column a INT')
            ->addOperation('ADD column b VARCHAR(255)')
            ->setOption('recursion-method', 'none')
            ->setOption('progress', 'percentage,1')
            ->setOption('no-drop-old-table')
            ->setExecuteMode();

        $this->assertEquals(
            'pt-online-schema-change --execute --recursion-method none --progress percentage,1 --no-drop-old-table --alter "ADD column a INT, ADD column b VARCHAR(255)" h=$HOST,D=$DATABASE,t=customers,u=$USER,p=$PASSWORD',
            $command->toString(true)
        );
    }

    public function testToArrayWithOptions(): void
    {
        $command = (new Command())
            ->setHost('$HOST')
            ->setDatabase('$DATABASE')
            ->setTable('customers')
            ->setUser('$USER')
            ->setPassword('$PASSWORD')
            ->addOperation('ADD column a INT')
            ->addOperation('ADD column b VARCHAR(255)')
            ->setOption('recursion-method', 'none')
            ->setOption('progress', 'percentage,1')
            ->setOption('no-drop-old-table')
            ->setMode(Command::MODE_DRY_RUN);

        $this->assertEquals(
            [
                'host' => '$HOST',
                'database' => '$DATABASE',
                'table' => 'customers',
                'user' => '$USER',
                'password' => '********',
                'operations' => [
                    'ADD column a INT',
                    'ADD column b VARCHAR(255)',
                ],
                'options' => [
                    'recursion-method' => 'none',
                    'progress' => 'percentage,1',
                    'no-drop-old-table' => '',

                ],
                'mode' => 'dry-run',
            ],
            $command->toArray()
        );
    }
}
