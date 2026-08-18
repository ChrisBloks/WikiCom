<?php
require_once "./src/tools/traits/tErrorMessageCollector.php";
switch ("DANNY") {
    case "DANNY":
        include_once "./config/danny.php";
        break;
    case "MARUISPC":
        include_once "./config/marius.php";
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
