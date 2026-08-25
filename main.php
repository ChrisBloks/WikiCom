<?php

// views
require_once "./src/views/BasePage.php";
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/views/containers/Header.php";
require_once "./src/views/containers/Title.php";
require_once "./src/views/containers/AuthorText.php";
require_once "./src/views/containers/Footer.php";
require_once "./src/views/containers/BodyText.php";
require_once "./src/views/containers/Image.php";
require_once "./src/views/containers/CodeBlock.php";
require_once "./src/views/HtmlDoc.php";
require_once "./src/views/containers/AtomicElement.php";
require_once "./src/views/containers/Form.php";
require_once "./src/views/fields/select.php";
require_once "./src/views/fields/CheckBoxGroup.php";
require_once "./src/views/fields/InputField.php";
require_once "./src/views/fields/TextAreaField.php";
require_once "./src/views/fields/buttonField.php";
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/views/Table.php";

//tools
require_once "./src/tools/interfaces/iController.php";
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/traits/tElementContainer.php";
require_once "./src/tools/utils/HtmlUtils.php";
require_once "./src/tools/utils/Utils.php";
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/traits/tElementContainer.php";

require_once "./src/controllers/MainController.php";
require_once "./src/controllers/factories/PageFactory.php";
require_once "./src/controllers/PageController.php";
require_once "./src/controllers/factories/FormFactory.php";
require_once "./src/controllers/factories/MenuFactory.php";
require_once "./src/controllers/validators/BaseValidator.php";
require_once "./src/controllers/validators/ContactValidator.php";
require_once "./src/controllers/ValidateRequest.php";
require_once "./src/controllers/UserHandler.php";

require_once "./src/tools/traits/tSingleton.php";

//models
require_once "./src/models/ModelSelector.php";

session_start();
$controller = new MainController();
$controller->main();
