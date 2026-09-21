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
        parent::__construct(
            name: $element_info['name'] ?? "",
            label: $element_info['label'] ?? "",
            class: $element_info['class'] ?? "");
        $this->options = $element_info['options_info'];
        $this->selected_option = $selected_option ?? $default_option;
        $this->option_class = "";
    }


    // TODO: Make sure the format works for rating and searchs
    public function show(): string
    {
        $ret = HtmlUtils::printLabel($this->id, $this->label)
            . '<select' . $this->baseAttribs() . ">";

        foreach ($this->options as $select_info) {
            $ret .= '<option '.
                    (!empty($option_class) ? 'class="'.$option_class.'"' : ""). //
                    'value="' . $select_info['value'] . '"'
                    .($select_info['value'] == $this->selected_option ? ' selected' : '') . ">"
                    .$select_info['label'].
                '</option>';
        }


        return $ret .= "</select>";
    }
}
