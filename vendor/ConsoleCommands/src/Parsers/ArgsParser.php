<?php

namespace ConsoleCommands\Parsers;
/**
 * 3. Парсит аргументы и выявляет имя каманды, ее аргументы и параметры
 */
class ArgsParser
{
    private string $reArg = '#\{(\w+)\}#';
    private string $reParam = '#\[(\w+)=(\w+)\]#';

    private string $commandName = '';
    private array $commandArgs = [];
    private array $commandParams = [];

    public function parse(array $args): void
    {
        unset($args[0]);
        $this->commandName = array_shift($args);
        foreach ($args as $arg) {
            $this->isCommandArg($arg) ?: $this->isCommandParam($arg) ?: ($this->commandArgs[] = $arg);
        }
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function getCommandArgs(): array
    {
        return $this->commandArgs;
    }

    public function getCommandParams(): array
    {
        return $this->commandParams;
    }

    private function isCommandArg($arg): bool
    {
        preg_match('#\{(\w+)\}#', $arg, $matches);
        if ($matches !== []) {
            $this->commandArgs[] = $matches[1];
            return true;
        }
        return false;
    }

    private function isCommandParam($arg): bool
    {
        preg_match('#\[(\w+)=(.+)\]#', $arg, $matches);
        if ($matches !== []) {
            $this->commandParams[$matches[1]][] = $matches[2];
            return true;
        }
        return false;
    }
}