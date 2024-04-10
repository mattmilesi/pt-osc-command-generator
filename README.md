# pt-command-generator

Generates pt-online-schema-change commands for MySQL, given the ALTER TABLE query.

⚠️ This is an experimental version. Please, do not use it in production.

## Installation

```bash
composer require fattureincloud/pt-command-generator
```

## Usage

```PHP
use MadBit\PtCommandGenerator\Generator;

$query = "ALTER TABLE customers ADD COLUMN middle_name VARCHAR(255) NOT NULL AFTER first_name;";
$generator = new Generator($query);
$commands = $generator->getCommands();

# $commands will be an array of strings, each one representing a command to be executed
# $commands[0]: pt-online-schema-change --alter "ADD COLUMN middle_name VARCHAR(255) NOT NULL AFTER first_name" h=$HOST,D=$DBNAME,t=customers,u=$DBUSERNAME,p=$DBPWD
```