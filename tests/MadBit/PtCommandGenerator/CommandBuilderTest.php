<?php

namespace MadBit\PtCommandGenerator;

use PHPUnit\Framework\TestCase;

class CommandBuilderTest extends TestCase
{
    public function testSetTable(): void
    {
        $builder = new CommandBuilder();
        $builder->setTable('customers');
        $this->assertEquals('customers', $builder->getTable());
    }

    public function testAddOperation(): void
    {
        $builder = new CommandBuilder();
        $builder->addOperation('ADD column a INT');
        $builder->addOperation('ADD column b VARCHAR(255)');
        $this->assertCount(2, $builder->getOperations());
    }

    public function testBuild(): void
    {
        $builder = new CommandBuilder();
        $builder->setTable('customers');
        $builder->addOperation('ADD column a INT');
        $builder->addOperation('ADD column b VARCHAR(255)');
        $this->assertEquals('pt-online-schema-change --dry-run --recursion-method none --progress percentage,1 --no-drop-old-table --alter-foreign-keys-method auto --alter "ADD column a INT, ADD column b VARCHAR(255)" h=$HOST,D=$DBNAME,t=customers,u=$DBUSERNAME,p=$DBPWD', $builder->build());
    }

    public function testBuildWithExecute(): void
    {
        $builder = new CommandBuilder();
        $builder->setTable('customers');
        $builder->addOperation('ADD column a INT');
        $builder->addOperation('ADD column b VARCHAR(255)');
        $builder->setDryRun(false);
        $this->assertEquals('pt-online-schema-change --execute --recursion-method none --progress percentage,1 --no-drop-old-table --alter-foreign-keys-method auto --alter "ADD column a INT, ADD column b VARCHAR(255)" h=$HOST,D=$DBNAME,t=customers,u=$DBUSERNAME,p=$DBPWD', $builder->build());
    }
}
