<?php

namespace App\Commands;

use ConsoleCommands\Commands\AbstractCommand;

/**
 * Выводит на экран все переданные аргументы и параметры
 */
class ShowSelf extends AbstractCommand
{
    public function run(): void
    {
        parent::run();

        $message = "\n" .
            "Called command: {$this->name} \n\n" .
            "Arguments: \n";

        foreach ($this->args as $arg) {
            $message .= "  -  {$message}\n";
        }

        $message .= "\nOptions: \n";

        foreach ($this->params as $name => $values) {
            $message .= "  -  {$name}\n";

            foreach ($values as $value) {
                $message .= "      -  {$value}\n";
            }
        }

        print($message);
    }
}