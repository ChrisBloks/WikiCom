<?php
require_once "./src/tools/interfaces/iValidator.php";
require_once "./src/tools/traits/tErrorMessageCollector.php";

abstract class BaseValidator implements iValidator
{
    use tErrorMessageCollector;

    public array $field_inputs = [];

    public function validate(string $page_name): bool
    {
        // Get field names from the database
        $field_names = ModelSelector::getFormModel()->fetchFieldNames(page_name: $page_name);
        // Collect field values from the response
        foreach ($field_names as $name) {
            $this->field_inputs[$name] = Utils::getRequestVar(
                key: $name,
                frompost: true
            );
            // If field was left empty, log an error
            if (empty($this->field_inputs[$name])) {
                $this->logError(message: 'Field ' . $name . ' was not filled in!');
            }
        }

        // If there are errors, return false, otherwise call the page-specific validator to check the values
        if ($this->hasErrors()) {
            return false;
        } else {
            return $this->validate_fields(field_inputs: $this->field_inputs);
        }
    }

    abstract public function validate_fields(array $field_inputs): bool;
}
