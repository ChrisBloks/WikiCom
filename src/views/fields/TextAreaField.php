<?php
	/* Textarea field
	*  Marius 08-2026
	*  Class able to draw textarea's
	*/
require_once "./src/views/fields/BaseField.php";

require_once "./src/tools/interfaces/iElement.php";
class TextAreaField extends BaseField implements iElement {

    protected string $html;

    public function __construct(string $name, string $class, string $label = ""){
        parent::__construct($name, $label, $class);

        // rows and cols should be variable eventually
        $this->html .= '<textarea rows="5" 
                                cols="56" 
                                name="'.$name.'" 
                                class="'.$class.'">
                                </textarea><br>';
    }

    public function show(): string {
        return $this->html;
    }


}