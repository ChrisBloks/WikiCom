<?php
include './vendor/autoload.php';
include "./config/marius.php";
include "./config/CONFIG.php";

use Wiki\controllers\MainController, \ManKind\tools as TOOLS;



// Todo: configfile with LOGpath for error writing
TOOLS\dev\ErrorHandler::init();
TOOLS\dev\Logger::init(
    \Config::LOGPATH,
    TOOLS\dev\LogTarget::TO_LOG OR TOOLS\dev\LogTarget::TO_SCR,
    TOOLS\dev\LogLevel::LVL_ALLWAYS
);
try{
    session_start();
    $controller = new MainController();
    $controller->main();
}
catch (\Exception $e){
    TOOLS\dev\Logger::_error($e);
}
