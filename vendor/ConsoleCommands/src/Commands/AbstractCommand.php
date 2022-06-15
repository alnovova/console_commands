<?php

namespace ConsoleCommands\Commands;


abstract class AbstractCommand
{
    protected string $name = '';
    protected string $commandFile = '';
    protected array $args = [];
    protected array $params = [];

    public function __construct(string $name, string $commandFile, array $args, array $params)
    {
        $this->name = $name;
        $this->commandFile = $commandFile;
        $this->args = $args;
        $this->params = $params;
    }

    /**
     * Выполнение заданной логики с возможностью вывода информации в консоль
     *
     * @throws \Exception
     */
    public function run()
    {
        if (in_array('help', $this->args)) {
            $this->help();
            exit();
        }
    }

    /**
     * Выводит на экран описание комманды, если она зарегистрирована
     *
     * @return void
     * @throws \Exception
     */
    protected function help(): void
    {
        $jsonData = file_get_contents($this->commandFile);
        $jsonData = json_decode($jsonData, true);

        $message = $jsonData[$this->name]['description'] ?? '';
        if ($message === '') {
            throw new \Exception(
                "Не загеристрировано описание для комманды {$this->name}"
            );
        }
        print($message . "\n");
    }
}