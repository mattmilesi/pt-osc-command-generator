<?php

namespace PtOscCommandGenerator;

use InvalidArgumentException;
use PtOscCommandGenerator\Utils\StringUtils;

class Command
{
    public const MODE_DRY_RUN = 'dry-run';
    public const MODE_EXECUTE = 'execute';

    private array $operations = [];
    private array $options = [];
    private array $dsnOptions = [];
    private string $mode = self::MODE_DRY_RUN;

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
        if (!Option::isValid($option)) {
            throw new InvalidArgumentException("Invalid option '$option'");
        }
        $this->options[$option] = $value;
        return $this;
    }

    public function unsetOption(string $option): Command
    {
        if (!Option::isValid($option)) {
            throw new InvalidArgumentException("Invalid option '$option'");
        }
        unset($this->options[$option]);
        return $this;
    }

    public function getOption(string $option): ?string
    {
        if (!Option::isValid($option)) {
            throw new InvalidArgumentException("Invalid option '$option'");
        }
        return $this->options[$option] ?? null;
    }

    public function setDsnOption(string $dsnOption, string $value = ''): Command
    {
        if (!DsnOption::isValid($dsnOption)) {
            throw new InvalidArgumentException("Invalid DSN option '$dsnOption'");
        }
        $this->dsnOptions[$dsnOption] = $value;
        return $this;
    }

    public function unsetDsnOption(string $dsnOption): Command
    {
        if (!DsnOption::isValid($dsnOption)) {
            throw new InvalidArgumentException("Invalid DSN option '$dsnOption'");
        }
        unset($this->dsnOptions[$dsnOption]);
        return $this;
    }

    public function getDsnOption(string $dsnOption): ?string
    {
        if (!DsnOption::isValid($dsnOption)) {
            throw new InvalidArgumentException("Invalid DSN option '$dsnOption'");
        }
        return $this->dsnOptions[$dsnOption] ?? null;
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

        $optionsString = implode($fancy ? "\n    " : ' ', array_map(function ($key, $value) use ($fancy) {
            return "--{$key}" . ($value ? " {$value}" : '');
        }, array_keys($this->options), $this->options));

        $dsnOptionsString = implode(',', array_map(function ($key, $value) use ($showPassword) {
            if (!$showPassword && $key === DsnOption::PASSWORD) {
                $value = '******';
            }
            return "{$key}={$value}";
        }, array_keys($this->dsnOptions), $this->dsnOptions));

        $separator = $fancy ? "\n    " : ' ';
        return "pt-online-schema-change{$separator}{$optionsString}{$separator}{$dsnOptionsString}";
    }

    public function toArray(bool $showPassword = false): array
    {
        $dsnOptions = $this->dsnOptions;
        if (!$showPassword) {
            $dsnOptions[DsnOption::PASSWORD] = '******';
        }
        return [
            'operations' => $this->operations,
            'options' => $this->options,
            'dsnOptions' => $dsnOptions,
            'mode' => $this->mode,
        ];
    }
}
