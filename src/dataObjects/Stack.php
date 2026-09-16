<?php
namespace Wiki\dataObjects;
class Stack
{
    private array $stack = [];
    private int $count = 0;

    public function add($array){
        if (is_null($array)){
            return;
        }
        $this->count += count($array);
        foreach ($array as $value){
            $this-> stack[] = $value;
        }
    }

    public function pop( ){
        $this->count += -1;
        return array_pop($this->stack);
    }

    public function isEmpty(){
        return empty($this->stack);
    }

    public function show(){
        return $this->stack;
    }
}