<?php
// dont delete!
require_once "./PageFields.php";

// views
require_once "./src/views/BasePage.php";
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/views/containers/Header.php";
require_once "./src/views/containers/Footer.php";
require_once "./src/views/containers/BodyText.php";
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
require_once "./src/controllers/PageController.php";
require_once "./src/controllers/FormFactory.php";
require_once "./src/controllers/MenuFactory.php";

//models
require_once "./src/models/ModelSelector.php";

// $page_factory = new PageFactory('search',true);
// $htmlpage = $page_factory->getElementsByPage();
// $htmlpage->show();

//$controller = new PageController();
//$controller->showResponse();

// htmlutils::dump("test",ModelSelector::callModel("form")->getFieldInfo("logine"));

require_once "./src/tools/interfaces/iElement.php";
require_once "./src/tools/traits/tElementContainer.php";
require_once "./src/views//containers/ContainerElement.php";
require_once "./src/controllers/TableFactory.php";

$columns = [
    ['key' => 'title',       'label' => 'Title',       'type' => 'text'],
    ['key' => 'last_edited', 'label' => 'Last edited', 'type' => 'date'],
    ['key' => 'id',          'label' => 'Actions',     'type' => 'actions', 'class' => 'no-wrap'],
];

// standing in for $articleModel->getOwnedArticles($userId)
$rows = [
    ['id' => 1, 'title' => 'PHP OOP Basics',            'last_edited' => '2026-07-12'],
    ['id' => 2, 'title' => 'Factory Pattern Deep Dive', 'last_edited' => '2026-08-01'],
    ['id' => 3, 'title' => 'Draft: Untitled',           'last_edited' => '2026-08-10'],
];

$tableFactory = new TableFactory();
$table = $tableFactory->createTable($columns, $rows);

echo $table->show();