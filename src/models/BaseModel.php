<?php
/* BaseModel
*  Danny
*  08/2026
*  Base model class is where all other classes extend and it establishes connection with crud
*/

namespace Wiki\models;

use Wiki\tools\traits\tErrorMessageCollector;


/**
 * Model class that defines basic behaviour.
 * @uses tErrorMessageCollector
 */
abstract class BaseModel
{
    use tErrorMessageCollector;
    protected Crud $crud;

    public function __construct()
    {
        $this->crud = Crud::getInstance();
    }
}
