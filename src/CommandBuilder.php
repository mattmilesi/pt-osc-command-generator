<?php

namespace PtOscCommandGenerator;

use PtOscCommandGenerator\Utils\StringUtils;

class CommandBuilder
{
    private const MODE_DRY_RUN = 'dry-run';
    private const MODE_EXECUTE = 'execute';
    private string $host;
    private string $database;
    private string $table;
    private string $user;
    private string $password;
    private array $operations = [];
    private array $options = [];
    private string $mode = self::MODE_DRY_RUN;

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): CommandBuilder
    {
        $this->host = $host;

        return $this;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function setDatabase(string $database): CommandBuilder
    {
        $this->database = $database;

        return $this;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table): CommandBuilder
    {
        $this->table = $table;

        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): CommandBuilder
    {
        $this->user = $user;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): CommandBuilder
    {
        $this->password = $password;

        return $this;
    }

    public function addOperation(string $operation): CommandBuilder
    {
        $this->operations[] = $operation;

        return $this;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    public function isDryRun(): bool
    {
        return $this->mode === self::MODE_DRY_RUN;
    }

    public function setOption(string $option, string $value = ''): CommandBuilder
    {
        // TODO: filtering
        $this->options[$option] = $value;
        return $this;
    }

    public function unsetOption(string $option): CommandBuilder
    {
        // TODO: filtering
        unset($this->options[$option]);
        return $this;
    }

    public function getOption(string $option): ?string
    {
        return $this->options[$option] ?? null;
    }

    public function setDryRunMode(bool $dryRun): CommandBuilder
    {
        $this->mode = $dryRun ? self::MODE_DRY_RUN : self::MODE_EXECUTE;
        return $this;
    }

    public function setExecuteMode(bool $execute): CommandBuilder
    {
        $this->mode = $execute ? self::MODE_EXECUTE : self::MODE_DRY_RUN;
        return $this;
    }

    public function build(): string
    {
        $operations = StringUtils::encodeDoubleQuotedArgument(implode(', ', $this->operations));

        unset($this->options[self::MODE_DRY_RUN]);
        unset($this->options[self::MODE_EXECUTE]);
        $this->options[$this->mode] = '';
        $this->options['alter'] = '"' . $operations . '"';

        $optionsString = implode(' ', array_map(function ($key, $value) {
            return "--{$key}" . ($value ? " {$value}" : '');
        }, array_keys($this->options), $this->options));

        return "pt-online-schema-change " . $optionsString . " h={$this->host},D={$this->database},t={$this->table},u={$this->user},p={$this->password}";
    }
}
