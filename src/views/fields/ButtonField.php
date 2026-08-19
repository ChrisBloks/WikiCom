<?php
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/views/fields/BaseField.php";
class ButtonField extends BaseField implements iElement {

    protected string $type;
    public function __construct(string $type, string $name, string $class, string $label = ""){
        parent::__construct($name, $label, $class);
        $this->type = $type;
    }

    public function show(): string {
        return 
                '<input type="'.$this->type.'" 
                        name="'.$this->name.'" 
                        id="'.$this->id. '" 
                        value="' .$this->label.'" 
                        class="'.$this->class.'" ><br>';
        }


}