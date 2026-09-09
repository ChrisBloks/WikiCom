<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, Wiki\tools\utils\HtmlUtils;

class InputField extends BaseField
{

    protected string $type;
    protected string $text;
    protected string $name;
    public function __construct(string $type, string $name, string $class, string $label = "", string $text = "", string $id = "")
    {
        parent::__construct($name, $label, $class);
        $this->type = $type;
        $this->text = $text;
        $this->name = $name;
        if (!empty($id)) {
            $this->id = $id;
        }
    }

    public function show(): string
    {

        return HtmlUtils::printLabel($this->id, $this->label)
            . '<input type="' . $this->type . '" 
                        name="' . $this->name . '" 
                        id="' . $this->id . '" 
                        value="' . $this->text . '" 
                        class="' . $this->class . '" ><br>';

                        
    }
}
