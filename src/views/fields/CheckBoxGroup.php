<?php
require_once "./src/tools/interfaces/iElement.php";
require_once "./src/views/fields/BaseField.php";

class CheckBoxGroup extends BaseField{

protected array $options =[];

    public function __construct(string $name, string $label, string $class, array $options,array $value){
        parent::__construct($name, $label, $class);    
        $this->options = $options;
        $this->value = $value;
    }


    public function show():string{
        
        $ret = '<div class="checkbox_group">';
        $ret .= HtmlUtils::printLabel($this->id, $this->label)."";

        foreach ($this->options as $value => $display){
            $checked = $this->value[$value];
            $ret .= '<input type="checkbox"'. $this->baseAttribs(true, $value). 'value="'.$display.'"'. ($checked? 'checked' : '') .'>';
            $ret .= HtmlUtils::printLabel($this->id, $display). "";            
        }
                    
        return $ret .= "</div>";
    } 
}