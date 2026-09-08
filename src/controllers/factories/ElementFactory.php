<?php

use Wiki\tools\interfaces\iElement;
use Wiki\views\containers\AtomicElement;

class ElementFactory {

    // TODO: implement
    public function createNewElement($element_info): iElement{
        return new AtomicElement("");
    }
}