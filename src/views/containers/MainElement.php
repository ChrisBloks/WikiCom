<?php
namespace Wiki\views\containers;

class MainElement extends ContainerElement{


    public function __construct(){
        parent::__construct('<main class="main-content">','</main>');
    }
}