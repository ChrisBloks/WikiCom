<?php

namespace Wiki\controllers\factories;

use Wiki\tools\interfaces\iElement;
use Wiki\views\containers\AtomicElement;
use Wiki\views\containers\ContainerElement;

class ElementFactory {

    // TODO: implement
    public function createNewElement(array $element_info): iElement{
        switch ($element_info['php_class']){
            
            case 'ContainerElement':
                $element = new ContainerElement(
                    html_before: "<{$element_info['tag']}>",
                    html_after: ""
                );
                break;
            case 'EmptyContainer':
            default:
                $element = new ContainerElement(
                    html_before: "",
                    html_after: ""
                );
                return $element;
                break;
        }
    }
}