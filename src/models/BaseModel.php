<?php
/* BaseModel
*  Danny
*  08/2026
*  Base model class is where all other classes extend and it establishes connection with crud
*/
require_once "./src/tools/traits/tErrorMessageCollector.php";
$user = (isset($_ENV["USERDOMAIN"])) ? $_ENV["USERDOMAIN"] :"MARIUS";
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
