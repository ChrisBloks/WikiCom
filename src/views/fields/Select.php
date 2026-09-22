<?php

namespace Wiki\views\fields;

use Wiki\dataObjects\ElementInfo;
use Wiki\tools\utils\HtmlUtils;

class Select extends BaseField
{

    protected array $options = [];
    protected string $selected_option;
    protected string $option_class;
    public function __construct(ElementInfo $element_info, ?string $selected_option = null, string $default_option = 'rating')
        //string $name, string $label, string $class, array $options, string $selected_option = "", string $option_class = ""
    {   
        $field_info = $element_info['field_info'];
       
        parent::__construct(
            name: $field_info['name'] ?? "",
            label: $field_info['label'] ?? "",
            class: $field_info['html_class'] ?? "");
        $this->options = $element_info['options_info'];
        $this->selected_option = $selected_option ?? $default_option;
        $this->option_class = "";
    }


    // TODO: Make sure the format works for rating and searchs
    public function show(): string
    {
        $ret = HtmlUtils::printLabel($this->id, $this->label)
            . '<select' . $this->baseAttribs() . ">";

        foreach ($this->options as $option) {
            $ret .= '<option '.
                    (!empty($this->option_class) ? 'class="'.$this->option_class.'"' : ""). //
                    'value="' . $option['id'] . '"'
                    .($option['name'] == $this->selected_option ? ' selected' : '') . ">"
                    .$option['name'].
                '</option>';
        }


        return $ret .= "</select>";
    }
}
