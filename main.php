<?php
include './vendor/autoload.php';
$user = (isset($_ENV["USERDOMAIN"])) ? $_ENV["USERDOMAIN"] : "MARIUS";
switch ($user) {
    case "DANNY":
        include_once "./config/danny.php";
        break;
    case "MARUISPC":
        include_once "./config/marius.php";
        break;
    case "MARIUS":
        include_once "./config/marius.php";
        break;
    case "WORKGROUP":
        include_once "./config/christian.php";
        break;
}

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
