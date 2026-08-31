<?php

namespace Wiki\controllers\validators;

use Wiki\tools\interfaces\iValidator,
    Wiki\tools\traits\tErrorMessageCollector;


class ImgValidator implements iValidator
{
    use tErrorMessageCollector;
    protected array $field_inputs = [];
        public function validate(string $name): bool
    {
        // Save name of field in inputs
        $this->field_inputs['name'] = $name;
        // Get name of file
        $this->field_inputs[$name] = $_FILES[$name]['name'];
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

    public function getFieldInputs(): array
    {
        return $this->field_inputs;
    }

    public function validateFields(array $field_inputs): bool
    {
        $target_dir = \Config::AUTHORIMGPATH;
        $filevar = $_FILES[$field_inputs['name']];
        $target_file = $target_dir . basename($filevar["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($filevar["tmp_name"]);
        if($check == false) {
            $this->logError("File is not an image.");
            return false;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $this->logError("Sorry, file already exists.");
            return false;
        }

        // Check file size
        if ($filevar["size"] > 500000) {
            $this->logError("Sorry, your file is too large.");
            return false;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $this-> logError("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            return false;
        }

        return true;
    }
}