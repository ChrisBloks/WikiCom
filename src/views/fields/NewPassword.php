<?php

namespace Wiki\views\fields;

use Wiki\tools\interfaces\iElement, Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;

class NewPassword extends BaseField implements iElement
{

    protected string $type;
    protected string $text;
    protected string $name;
    public function __construct(string $name = "", string $class = "", string $label = "")
    {
        parent::__construct($name, $label, $class);
    }

    public function show(): string
    {
        return HtmlUtils::printLabel($this->id, "Password: ")
                . '<input type=     "password" 
                            name=   "password_1" 
                            id="' . $this->id . '" 
                            value=  "" 
                            class="' . $this->class . '" ><br>'.
                HtmlUtils::printLabel($this->id, "Verify Password: ")
                . '<input type=     "password" 
                            name=   "password_2" 
                            id="' . $this->id . '" 
                            value=  "" 
                            class="' . $this->class . '" ><br>';
    }
}
