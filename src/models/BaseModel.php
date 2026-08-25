<?php
/* BaseModel
*  Danny
*  08/2026
*  Base model class is where all other classes extend and it establishes connection with crud
*/

namespace Wiki\models;

use Wiki\tools\traits\tErrorMessageCollector;

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

abstract class BaseModel
{
    use tErrorMessageCollector;
    protected Crud $crudTemp;

    public function __construct()
    {
        $this->crudTemp = Crud::getInstance();
    }
}
