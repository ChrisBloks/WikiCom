<?php
/* BaseModel
*  Danny
*  08/2026
*  Base model class is where all other classes extend and it establishes connection with crud
*/

namespace Wiki\models;

use Wiki\tools\traits\tErrorMessageCollector;


abstract class BaseModel
{
    use tErrorMessageCollector;
    protected Crud $crudTemp;

    public function __construct()
    {
        $this->crudTemp = Crud::getInstance();
    }
}
