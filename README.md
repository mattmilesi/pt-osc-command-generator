# pt-command-generator

Generates pt-online-schema-change commands for MySQL, given the ALTER TABLE query.

⚠️ This is an experimental version. Please, do not use it in production.

## Installation

```bash
composer require mattmilesi/pt-osc-command-generator
```

## Usage

```PHP
use PtOscCommandGenerator\Generator;

$query = "ALTER TABLE customers ADD COLUMN middle_name VARCHAR(255) NOT NULL AFTER first_name;";
$generator = new Generator($query);
$commands = $generator->getCommands();

# $commands will be an array of strings, each one representing a command to be executed
# $commands[0]: pt-online-schema-change --alter "ADD COLUMN middle_name VARCHAR(255) NOT NULL AFTER first_name" h=$HOST,D=$DBNAME,t=customers,u=$DBUSERNAME,p=$DBPWD
```

## License  
This project is licensed under the Apache License 2.0.  
However, it includes the `phpmyadmin/sql-parser` library, which is used under **GPL 3.0** to ensure compatibility.  
See [GPL-3.0.txt](./GPL-3.0.txt) for details.  
