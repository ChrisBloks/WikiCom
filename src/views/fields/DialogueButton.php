<?php

namespace Wiki\views\fields;

use Wiki\tools\dataObjects\ElementInfo;

class DialogueButton extends ButtonField{
    public function __construct(ElementInfo $element_info){
        parent::__construct($element_info);
    }
}