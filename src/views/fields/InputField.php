<?php
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/views/fields/BaseField.php";
class InputField extends BaseField implements iElement {

    protected string $type;
    protected array $text;
    protected string $name;
    public function __construct(string $type, string $name, string $class, string $label = "", array $text = []){
        parent::__construct($name, $label, $class);
        $this->type = $type;
        $this->text = $text;
        $this->name = $name;
        $this ->value = ((empty($this->text[$this->name])) ? '':$this->text[$this->name]);
    }

    public function show(): string {
        return HtmlUtils::printLabel($this->id, $this->label) 
                .'<input type="'.$this->type.'" 
                        name="'.$this->name.'" 
                        id="'.$this->id. '" 
                        value="' .$this->value.'" 
                        class="'.$this->class.'" ><br>';
        }


}