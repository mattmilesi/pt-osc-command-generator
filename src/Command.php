<?php

namespace PtOscCommandGenerator;

use InvalidArgumentException;
use PtOscCommandGenerator\Utils\StringUtils;

class Command
{
    public const MODE_DRY_RUN = 'dry-run';
    public const MODE_EXECUTE = 'execute';

    private string $host = '';
    private string $database = '';
    private string $table = '';
    private string $user = '';
    private string $password = '';
    private array $operations = [];
    private array $options = [];
    private string $mode = self::MODE_DRY_RUN;

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): Command
    {
        $this->host = $host;

        return $this;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function setDatabase(string $database): Command
    {
        $this->database = $database;

        return $this;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table): Command
    {
        $this->table = $table;

        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): Command
    {
        $this->user = $user;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): Command
    {
        $this->password = $password;

        return $this;
    }

    public function addOperation(string $operation): Command
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

    public function setOption(string $option, string $value = ''): Command
    {
        // TODO: validate options
        $this->options[$option] = $value;
        return $this;
    }

    public function unsetOption(string $option): Command
    {
        // TODO: validate options
        unset($this->options[$option]);
        return $this;
    }

    public function getOption(string $option): ?string
    {
        return $this->options[$option] ?? null;
    }

    public function setMode(string $mode): Command
    {
        if (!in_array($mode, [self::MODE_DRY_RUN, self::MODE_EXECUTE])) {
            throw new InvalidArgumentException('Invalid mode');
        }
        $this->mode = $mode;
        return $this;
    }

    public function setDryRunMode(bool $dryRun = true): Command
    {
        $this->mode = $dryRun ? self::MODE_DRY_RUN : self::MODE_EXECUTE;
        return $this;
    }

    public function setExecuteMode(bool $execute = true): Command
    {
        $this->mode = $execute ? self::MODE_EXECUTE : self::MODE_DRY_RUN;
        return $this;
    }

    public function __toString(): string
    {
        return $this->toString(true);
    }

    public function toString(bool $showPassword = false, bool $fancy = false): string
    {
        $operations = StringUtils::encodeDoubleQuotedArgument(implode(', ', $this->operations));

        unset($this->options[self::MODE_DRY_RUN]);
        unset($this->options[self::MODE_EXECUTE]);
        $this->options = array_merge(
            [$this->mode => ''],
            $this->options,
            ['alter' => '"' . $operations . '"']
        );

        $optionsString = implode(' ', array_map(function ($key, $value) use ($fancy) {
            return ($fancy ? "\n    " : '') . "--{$key}" . ($value ? " {$value}" : '');
        }, array_keys($this->options), $this->options));

        $password = $showPassword ? $this->password : '********';
        return "pt-online-schema-change " . $optionsString . ($fancy ? "\n    " : ' ') . "h={$this->host},D={$this->database},t={$this->table},u={$this->user},p={$password}";
    }

    public function toArray(bool $showPassword = false): array
    {
        return [
            'host' => $this->host,
            'database' => $this->database,
            'table' => $this->table,
            'user' => $this->user,
            'password' => $showPassword ? $this->password : '********',
            'operations' => $this->operations,
            'options' => $this->options,
            'mode' => $this->mode,
        ];
    }
}
