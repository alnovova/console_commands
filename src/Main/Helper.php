<?php

namespace App\Main;

use ConsoleCommands\Parsers\ArgsParser;


/**
 * Основной класс приложения
 */
class Helper
{
    private string $fileName;
    private array $registeredCommands = [];
    private ArgsParser $parser;
    private array $args;
    private array $commandData = [];

    public function __construct(string $file, ArgsParser $parser, array $args)
    {
        $this->fileName = $file;
        $this->parser = $parser;
        $this->args = $args;
    }

    /**
     * Обработка входящих аргументов запуска
     * Запускает команду, если она зарегистрирована или выводит пользователю сообщение
     *
     * @return void
     */
    public function run(): void
    {
        try {
            $jsonData = file_get_contents($this->fileName);
            $jsonData = json_decode($jsonData, true);
            $this->registeredCommands = $jsonData ?? [];

            if (count($this->args) === 1) {
                $this->showCommands();
                return;
            }

            $this->parser->parse($this->args);
            $commandName = $this->parser->getCommandName();
            $this->commandData = $this->registeredCommands[$commandName] ?? [];

            if ($this->commandData === []) {
                print("Команда {$commandName} не зарегистрирована");
                return;
            }

            $this->runCommand();

        } catch (\Exception $exc) {
            print($exc->getMessage() . "\n");
        }
    }

    /**
     * Показать все зарегистрированные команды
     *
     * @return void
     * @throws \Exception
     */
    private function showCommands(): void
    {
        if ($this->registeredCommands === []) {
            throw new \Exception(
                "В приложении пока не зарегистрировано ни одной команды"
            );
        }

        $count = 0;
        foreach ($this->registeredCommands as $name => $data) {
            $count++;
            print(
                $count . '. ' . $name . "\n" .
                $data['description'] . "\n\n"
            );
        }
    }

    /**
     * Запустить команду
     *
     * @return void
     * @throws \Exception
     */
    private function runCommand(): void
    {
        $class = $this->commandData['class'] ?? '';
        if ($class === '') {
            throw new \Exception(
                "Не загеристрирован класс с логикой для комманды {$this->parser->getCommandName()}"
            );
        }

        $params = $this->parser->getCommandParams();

        $command = new $this->commandData['class'](
            $this->parser->getCommandName(),
            $this->fileName,
            $this->parser->getCommandArgs(),
            $params
        );
        $command->run();
    }
}
