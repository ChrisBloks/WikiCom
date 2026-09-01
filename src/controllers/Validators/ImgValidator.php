<?php

namespace Wiki\controllers\validators;

use Wiki\tools\interfaces\iValidator,
    Wiki\tools\traits\tErrorMessageCollector;


class ImgValidator implements iValidator
{
    use tErrorMessageCollector;
    protected array $field_inputs = [];
        public function validate(string $name, bool $optional = false): bool
    {
        // Get name of file
        $this->field_inputs[$name] = $_FILES[$name]['name'];
        // If field was left empty, log an error
        if (empty($this->field_inputs[$name])) {
            if ($optional == false) {
            $this->logError(
                message: 'Field ' . $name . ' was not filled in!',
                key: $name
            );
            }
        }
        

        // If there are errors, return false, otherwise call the page-specific validator to check the values
        if ($this->hasErrors() or empty($this->field_inputs[$name])) {
            return false;
        } else {
            return $this->validateFields(field_inputs: $this->field_inputs);
        }
    }

    public function getFieldInputs(): array
    {
        return $this->field_inputs;
    }

    public function validateFields(array $field_inputs): bool
    {
        $target_dir = \Config::AUTHORIMGPATH;
        // use the same key of field inputs to find the image file
        $key_name = array_key_first($field_inputs);
        $filevar = $_FILES[$key_name];
        $target_file = $target_dir . basename($filevar["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($filevar["tmp_name"]);
        if($check == false) {
            $this->logError(
                message: "File is not an image.",
                key: $key_name
            );
            return false;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $this->logError(
                message: "Sorry, file already exists.",
                key: $key_name
            );
            return false;
        }

        // Check file size
        if ($filevar["size"] > 500000) {
            $this->logError(
                message: "Sorry, your file is too large.",
                key: $key_name
            );
            return false;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $this->logError(
                message: "Sorry, only JPG, JPEG, PNG & GIF files are allowed.",
                key: $key_name
            );
            return false;
        }

        $this->field_inputs['filevar'] = $filevar;
        return true;
    }
}