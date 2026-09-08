<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, Wiki\tools\interfaces\iElement;

class ButtonField extends BaseField implements iElement
{
    protected string $type;
    protected ?string $href;

    public function __construct(
        string $type,
        string $name,
        string $class,
        string $label = "",
        ?string $href = null
    ) {
        parent::__construct($name, $label, $class);
        $this->type = $type;
        $this->href = $href;
    }

    public function show(): string
    {
        $input =
            '<input type="' . $this->type . '" 
                        name="' . $this->name . '" 
                        id="' . $this->id . '" 
                        value="' . htmlspecialchars($this->label) . '" 
                        class="' . $this->class . '" >';

        if ($this->href !== null) {
            return '<a href="' . $this->href . '" style="text-decoration:none;">' . $input . '</a><br>';
        }

        return $input . '<br>';
    }
}
