<?php

namespace Wiki\views\fields;

use Wiki\views\fields\BaseField, Wiki\tools\interfaces\iElement;

/**
 * Class for adding a button field to a form
 * @var string type MUST BE BUTTON
 */
class ButtonField extends BaseField
{
    protected string $type;
    protected ?string $href;

    public function __construct(
        string $type,
        string $name,
        string $class,
        string $label = "",
        string $id = "",
        ?string $href = null
    ) {
        parent::__construct($name, $label, $class);
        $this->type = $type;
        $this->href = $href;
        if (!empty($id)){
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
                        class="' . $this->class . '" >';

        if ($this->href !== null) {
            return '<a href="' . $this->href . '">' . $input . '</a><br>';
        }

        return $input . '<br>';
    }
}
