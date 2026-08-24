<?php
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/views/fields/BaseField.php";
class InputField extends BaseField implements iElement {

    protected string $type;
    protected string $text;
    protected string $name;
    public function __construct(string $type, string $name, string $class, string $label = "", string $text = ""){
        parent::__construct($name, $label, $class);
        $this->type = $type;
        $this->text = $text;
        $this->name = $name;
    }

    public function show(): string {
        return HtmlUtils::printLabel($this->id, $this->label) 
                .'<input type="'.$this->type.'" 
                        name="'.$this->name.'" 
                        id="'.$this->id. '" 
                        value="' .$this->text.'" 
                        class="'.$this->class.'" ><br>';
        }


}