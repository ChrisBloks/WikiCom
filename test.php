<?php

// views
require_once "./src/views/BasePage.php";
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/views/containers/Header.php";
require_once "./src/views/containers/Title.php";
require_once "./src/views/containers/Footer.php";
require_once "./src/views/containers/BodyText.php";
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
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/traits/tElementContainer.php";
require_once "./src/tools/utils/HtmlUtils.php";


require_once "./src/controllers/PageFactory.php";
require_once "./src/controllers/PageController.php";
require_once "./src/controllers/FormFactory.php";
require_once "./src/controllers/MenuFactory.php";
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/traits/tElementContainer.php";

//models
require_once "./src/models/ModelSelector.php";



$controller = new PageController();
$controller->showResponse();
