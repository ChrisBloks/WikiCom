<?php
// dont delete!
require_once "./PageFields.php";

// views
require_once "./src/views/BasePage.php";
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/views/containers/Header.php";
require_once "./src/views/containers/Footer.php";
require_once "./src/views/HtmlDoc.php";
require_once "./src/views/containers/AtomicElement.php";
require_once "./src/views/containers/Form.php";
require_once "./src/views/fields/select.php";
require_once "./src/views/fields/CheckBoxGroup.php";
require_once "./src/views/fields/InputField.php";
require_once "./src/views/fields/TextAreaField.php";


//tools
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/traits/tElementContainer.php";
require_once "./src/tools/utils/HtmlUtils.php";


require_once "./src/controllers/PageFactory.php";
require_once "./src/controllers/FormFactory.php";
require_once "./src/controllers/MenuFactory.php";

$page_factory = new PageFactory_v1('search');
$htmlpage = $page_factory->getElementsByPage();
$htmlpage->show();
