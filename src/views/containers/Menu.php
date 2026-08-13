<?php
	/* Menu
	*  Marius
	*  Draws menu items
	*/

class Menu extends ContainerElement{
    // input: label and href

    public function __construct()
    {
        parent::__construct('<ul>','</ul>');
    }

}