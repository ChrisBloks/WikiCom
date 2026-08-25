<?php
require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

//tools
require_once "./src/tools/interfaces/iController.php";
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/interfaces/iRequestHandler.php";
require_once "./src/tools/traits/tElementContainer.php";
require_once "./src/tools/utils/HtmlUtils.php";
require_once "./src/tools/utils/Utils.php";
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/traits/tElementContainer.php";
require_once "./src/tools/traits/tSingleton.php";

// Controllers
require_once "./src/controllers/MainController.php";
require_once "./src/factories/PageFactory.php";
require_once "./src/controllers/PageController.php";
require_once "./src/factories/FormFactory.php";
require_once "./src/factories/MenuFactory.php";
require_once "./src/controllers/validators/BaseValidator.php";
require_once "./src/controllers/validators/RegisterValidator.php";
require_once "./src/controllers/ValidateRequest.php";
require_once "./src/controllers/UserHandler.php";
require_once "./src/controllers/requestHandlers/BaseRequestHandler.php";
require_once "./src/controllers/requestHandlers/GetRequestHandler.php";
require_once "./src/controllers/requestHandlers/PostRequestHandler.php";

//models
require_once "./src/models/ModelSelector.php";

//Exceptions
require_once "./src/tools/exceptions/PageNotFoundException.php";

// echo (PHP_VERSION);

session_start();
$controller = new MainController();
$controller->main();
