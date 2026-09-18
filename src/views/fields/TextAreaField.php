<?php
/* Textarea field
	*  Marius 08-2026
	*  Class able to draw textarea's
	*/

namespace Wiki\views\fields;

use Wiki\dataObjects\ElementInfo;
use Wiki\views\fields\BaseField, Wiki\tools\interfaces\iElement;

class TextAreaField extends BaseField implements iElement
{

    protected string $html;
    protected string $text;

    public function __construct(ElementInfo $element_info)
    {
        $field_info = $element_info['field_info'];
        parent::__construct(
            name: $field_info['name'] ?? "", 
            label: $field_info['label'] ?? "", 
            class: $field_info['html_class'] ?? ""
        );
        $this->text = $field_info['text'] ?? "";

        // rows and cols should be variable eventually
        $this->html .= '<textarea name="' . $this->name 
                    . '" class="' . $this->class . '">' 
                    . $this->text . '</textarea><br>';
    }

    public function show(): string
    {
        return $this->html;
    }
}
