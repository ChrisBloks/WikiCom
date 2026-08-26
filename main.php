<?php
include './vendor/autoload.php';
include "./config/christian.php";

use Wiki\controllers\MainController, \ManKind\tools as TOOLS;



// TODO : configfile with LOGpath for error writing
// ToDo: dsdsdsd
// @todo bfadb
// FIXME bfdabf
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
