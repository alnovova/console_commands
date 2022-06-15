<?php

namespace ConsoleCommands\Commands;


/**
 * Класс для регистрации команд
 */
class Registrar extends AbstractCommand
{
    /**
     * Регистрирует команду, задавая ей название и описание
     * Если команда с таким названием уе существует - перезаписывает ее
     * Возвращает файла со списком команд после записи
     * или false - если запись не удалась
     *
     * @return int|false
     * @throws \Exception
     */
    public function run(): int|false
    {
        parent::run();

        if (!isset($this->params['name'])) {
            throw new \Exception(
                "Не передано название команды для ее регистрации"
            );
        }

        $file = file_get_contents($this->commandFile);
        $commands = json_decode($file,true) ?: [];
        $commands[$this->params['name']] = [
            'description' => $this->params['description'] ?? '',
            'class' => $this->params['class'] ?? '',
        ];
        $result = file_put_contents($this->commandFile, json_encode($commands, JSON_UNESCAPED_UNICODE));

        if ($result === false) {
            throw new \Exception(
                "Не удалось зарегистрировать команду"
            );
        }

        print("Команда {$this->params['name']} успешно зарегистрирована\n");
        return $result;
    }
}