<?php

namespace Wiki\views\fields;

use Wiki\tools\interfaces\iElement, Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;
use ArrayAccess;

class NewPassword extends BaseField implements iElement
{

    protected string $type;
    protected string $text;
    protected string $name;
    public function __construct(ArrayAccess $element_info)
    {
        $field_info = $element_info['field_info'];
        parent::__construct(
            name: $field_info['name'] ?? "", 
            label: $field_info['label'] ?? "", 
            class: $field_info['html_class'] ?? ""
        );
    }

    public function show(): string
    {
        return HtmlUtils::printLabel($this->id, "Password: ")
                . '<input type=     "password" 
                            name=   "password_1" 
                            id=     "newpassword-1" 
                            value=  "" 
                            class="' . $this->class . '"
                            data-min-length="' . \Config::MIN_PW_LENGTH . '" ><br>'.
                HtmlUtils::printLabel($this->id, "Verify Password: ")
                . '<input type=     "password" 
                            name=   "password_2" 
                            id=     "newpassword-2" 
                            value=  "" 
                            class="' . $this->class . '" ><br>';
    }
}
