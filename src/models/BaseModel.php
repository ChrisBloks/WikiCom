<?php
require_once "./src/tools/traits/tErrorMessageCollector.php";
$user = $_ENV["USERDOMAIN"];
switch ($user) {
    case "DANNY":
        include_once "./config/danny.php";
        break;
    case "":
        break;
    case "":
        break;
}

require_once "Crud.php";
require_once "BaseModel.php";

abstract class BaseModel
{
    use tErrorMessageCollector;
    protected Crud $crudTemp;    

    public function __construct()
    {
        $this->crudTemp = Crud::getInstance();
    }
}
