<?php

namespace Wiki\controllers\validators;

use Wiki\tools\interfaces\iValidator,
    Wiki\tools\traits\tErrorMessageCollector,
    Wiki\models\ModelSelector,
    Wiki\tools\utils\Utils;
use Wiki\tools\utils\HtmlUtils;

/**
 * Text validation class that defines basic 1st line of defence validation. If succesful, calls more specific validation behaviour as defined in child classes.
 * @uses tErrorMessageCollector
 * @var array $field_inputs array containing user inputs
 */
class TextValidator implements iValidator
{
    use tErrorMessageCollector;

    protected array $field_inputs = [];

    /**
     * Checks if all fields were correctly filled in an optionally calls more specific validation behaviour.
     * @param string $name
     * @return bool true if all validation steps were succesful, false otherwise.
     */
    public function validate(string $name): bool
    {
        // Get post variable based on name given
        $this->field_inputs[$name] = Utils::getRequestVar(
            key: $name,
            frompost: true
        );
        // If field was left empty, log an error
        if (empty($this->field_inputs[$name])) {
            $this->logError(message: 'Field ' . $name . ' was not filled in!');
        }
        

        // If there are errors, return false, otherwise call the page-specific validator to check the values
        if ($this->hasErrors()) {
            return false;
        } else {
            return $this->validateFields(field_inputs: $this->field_inputs);
        }
    }


    /**
     * Retrieve the field inputs property
     * @return array
     */
    public function getFieldInputs(): array
    {
        return $this->field_inputs;
    }

    /**
     * Page-specific validation behaviour. Should be overwritten by child classes.
     * @param ?array $field_inputs user inputs
     * @return bool
     */
    public function validateFields(array $field_inputs): bool
    {
        return true;
    }
}
