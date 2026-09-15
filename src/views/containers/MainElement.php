<?php
namespace Wiki\views\containers;

use Wiki\dataObjects\ElementInfo;

/**
 * Starts the <main> element of an htmlpage within the body
 * this contains variable elements
 */
class MainElement extends ContainerElement{


    public function __construct(){
        $element_info = ['html_tag' => 'main','html_class'=>"main-content"];
        parent::__construct(new ElementInfo($element_info));
    }
}