<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, 
    Wiki\tools\interfaces\iElement,
    Wiki\tools\utils\HtmlUtils;

/**
 * Class for adding a button field to a form
 * @var string type MUST BE BUTTON
 */
class ButtonField extends BaseField
{
    protected string $type;
    protected ?string $href;
    protected array $attributes;

    public function __construct(
        string $type,
        string $name,
        string $class,
        string $label = "",
        string $id = "",
        ?string $href = null,
        array $attributes = []
    ) {
        parent::__construct($name, $label, $class);
        $this->type = $type;
        $this->href = $href;
        $this->attributes = $attributes;
        if (!empty($id)) {
            $this->id = $id;
        }
    }

    public function show(): string
    {
        $input =
            '<input type="' . $this->type . '" 
                        name="' . $this->name . '" 
                        id="' . $this->id . '" 
                        value="' . htmlspecialchars($this->label) . '" 
                        class="' . $this->class . '"' . HtmlUtils::addAttrs($this->attributes) . ' >';

        if ($this->href !== null) {
            return '<a href="' . $this->href . '">' . $input . '</a><br>';
        }

        return $input . '<br>';
    }
}
