<?php
namespace Wiki\views\containers;

/**
 * Starts the <main> element of an htmlpage within the body
 * this contains variable elements
 */
class MainElement extends ContainerElement{


    public function __construct(){
        parent::__construct('<main class="main-content">','</main>');
    }
}