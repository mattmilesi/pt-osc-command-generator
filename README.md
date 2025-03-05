# pt-command-generator

Generates pt-online-schema-change commands for MySQL, given the ALTER TABLE query.

⚠️ This is an experimental version. Please, do not use it in production.

## Installation

```bash
composer require mattmilesi/pt-osc-command-generator
```

## Usage

```PHP
$query = "ALTER TABLE customers ADD COLUMN middle_name VARCHAR(255) NOT NULL AFTER first_name;";
$parser = new \PtOscCommandGenerator\StatementParser($query);
$command = $parser->getCommands()[0]
    ->setHost('<host>')
    ->setDatabase('<database>')
    ->setUser('<user>')
    ->setPassword('<password>')
    ->setExecuteMode();
$cliCommand = (string)$command;

# getCommands returns an array of Command, each one representing a command to be executed
# $cliCommand: pt-online-schema-change --execute --alter "ADD COLUMN middle_name VARCHAR(255) NOT NULL AFTER first_name" h=<host>,D=<database>,t=customers,u=<user>,p=<password>
```

## License  
This project is licensed under the Apache License 2.0.  
However, it includes the `phpmyadmin/sql-parser` library, which is used under **GPL 3.0** to ensure compatibility.  
See [GPL-3.0.txt](./GPL-3.0.txt) for details.  
