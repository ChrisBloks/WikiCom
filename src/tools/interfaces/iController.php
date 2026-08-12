<?php

interface iController{
    private function getRequest(); // get request type
    private function validateRequest(); // logic tree for Get- and Post request
    private function showResponse(); // start building what to show

}