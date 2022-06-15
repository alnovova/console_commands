<?php

use App\Main\Helper;
use ConsoleCommands\Parsers\ArgsParser;

const COMMANDS_FILE = __DIR__ . '/commands.json';

require_once 'autoloader.php';
require_once 'vendor/ConsoleCommands/autoloader.php';

$parser = new ArgsParser();
$app = new Helper(
    COMMANDS_FILE,
    $parser,
    $argv
);
$app->run();
