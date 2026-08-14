<?php
require_once "./src/tools/interfaces/iValidator.php";
require_once "./src/tools/traits/tErrorMessageCollector.php";

abstract class BaseValidator implements iValidator{

    use tErrorMessageCollector;


    
    public function validate(array $field_info): bool{

        $result = true;
      // contact name = "", email = ""
      // search tag = [], athors = [], ORDERBY

        // foreach ($field_info as $field_def){
        //     if ($this->validateField($field_def) === false){
        //          $this->logError();
        //         $result = false;
        //     }
        // }


        return $result;
    }
}