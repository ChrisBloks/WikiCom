<?php
require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('wiki');
$log->pushHandler(new StreamHandler(__DIR__.'/logs/wiki.log', Logger::WARNING));

$log->warning('This is a warning message');
$log->error('This is an error message');


session_start();
$controller = new MainController();
$controller->main();
