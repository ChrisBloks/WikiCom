<?php
require_once "./src/tools/traits/tErrorMessageCollector.php";
abstract class BaseModel
{
    use tErrorMessageCollector;
    protected Crud $crudTemp;    

    public function __construct()
    {
        $this->crudTemp = Crud::getInstance();
    }
}
