<?php

namespace Wiki\views\fields;

use Wiki\dataObjects\ElementInfo;
use Wiki\tools\utils\HtmlUtils;

/**
 * Class for adding a button field to a form
 * @var string type MUST BE BUTTON
 */
class ButtonField extends BaseField
{
    protected string $type;
    protected ?string $href;
    protected array $attributes;

    public function __construct(ElementInfo $element_info) {
        $field_info = $element_info['field_info'];
        parent::__construct(
            name: $element_info['element_name'] ?? "", 
            label: $field_info['label'] ?? "", 
            class: $element_info['html_class'] ?? ""
        );

        $this->href = $element_info['href'] ?? null;
        $this->id = $element_info['html_id'] ?? '';

        $this->html = "";
        // Construct opening tag
        $this->html .= "<{$element_info['html_tag']} " .
                        'id="' . $this->id . '"'.
                        'class="'. $element_info['html_class'] .'"'. HtmlUtils::addAttrs($element_info['attributes'] ?? []) .
                        'type="'.$field_info['type'].'" '.
                        'value="'.$field_info['value'].'" '.
                        '>';

        $this->html .= ($field_info['text'] ?? ($element_info['text']?? ""));

        // closing tag
        $this->html .= "</{$element_info['html_tag']}><br>";

    }

    public function show(): string
    {
        if ($this->href !== null) {
            return '<a href="' . $this->href . '">' . $this->html . '</a><br>';
        }

        return $this->html . '';
    }
}
