<?php
abstract class BaseModel
{
    protected Crud $crudTemp;    

    public function __construct()
    {
        $this->crudTemp = Crud::getInstance();
    }
}
