<?php

namespace PtOscCommandGenerator;

use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testSetDsnOption(): void
    {
        $builder = new Command();
        $builder->setDsnOption(DsnOption::TABLE, 'customers');
        $this->assertEquals('customers', $builder->getDsnOption(DsnOption::TABLE));
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
            ->setDsnOption(DsnOption::HOST, '$HOST')
            ->setDsnOption(DsnOption::DATABASE, '$DATABASE')
            ->setDsnOption(DsnOption::TABLE, 'customers')
            ->setDsnOption(DsnOption::USER, '$USER')
            ->setDsnOption(DsnOption::PASSWORD, '$PASSWORD')
            ->addOperation('ADD column a INT')
            ->addOperation('ADD column b VARCHAR(255)')
            ->setDryRunMode();

        $this->assertEquals(
            'pt-online-schema-change --dry-run --alter "ADD column a INT, ADD column b VARCHAR(255)" h=$HOST,D=$DATABASE,t=customers,u=$USER,p=******',
            $command->toString()
        );
    }

    public function testToStringWithExecuteAndOptions(): void
    {
        $command = (new Command())
            ->setDsnOption(DsnOption::HOST, '$HOST')
            ->setDsnOption(DsnOption::DATABASE, '$DATABASE')
            ->setDsnOption(DsnOption::TABLE, 'customers')
            ->setDsnOption(DsnOption::USER, '$USER')
            ->setDsnOption(DsnOption::PASSWORD, '$PASSWORD')
            ->setOption(Option::RECURSION_METHOD, 'none')
            ->setOption(Option::PROGRESS, 'percentage,1')
            ->setOption(Option::NO_DROP_OLD_TABLE)
            ->addOperation('ADD column a INT')
            ->addOperation('ADD column b VARCHAR(255)')
            ->setExecuteMode();

        $this->assertEquals(
            'pt-online-schema-change --execute --recursion-method none --progress percentage,1 --no-drop-old-table --alter "ADD column a INT, ADD column b VARCHAR(255)" h=$HOST,D=$DATABASE,t=customers,u=$USER,p=$PASSWORD',
            $command->toString(true)
        );
    }

    public function testToArrayWithOptions(): void
    {
        $command = (new Command())
            ->setDsnOption(DsnOption::HOST, '$HOST')
            ->setDsnOption(DsnOption::DATABASE, '$DATABASE')
            ->setDsnOption(DsnOption::TABLE, 'customers')
            ->setDsnOption(DsnOption::USER, '$USER')
            ->setDsnOption(DsnOption::PASSWORD, '$PASSWORD')
            ->setOption(Option::RECURSION_METHOD, 'none')
            ->setOption(Option::PROGRESS, 'percentage,1')
            ->setOption(Option::NO_DROP_OLD_TABLE)
            ->addOperation('ADD column a INT')
            ->addOperation('ADD column b VARCHAR(255)')
            ->setMode(Command::MODE_DRY_RUN);

        $this->assertEquals(
            [
                'operations' => [
                    'ADD column a INT',
                    'ADD column b VARCHAR(255)',
                ],
                'options' => [
                    'recursion-method' => 'none',
                    'progress' => 'percentage,1',
                    'no-drop-old-table' => '',

                ],
                'dsnOptions' => [
                    'h' => '$HOST',
                    'D' => '$DATABASE',
                    't' => 'customers',
                    'u' => '$USER',
                    'p' => '******',
                ],
                'mode' => 'dry-run',
            ],
            $command->toArray()
        );
    }
}
