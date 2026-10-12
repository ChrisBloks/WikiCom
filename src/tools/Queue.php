<?php

namespace Wiki\tools;

use ArrayAccess;

class Queue {

    private array $items = [];

    public function __construct(array $item_list)
    {
        foreach($item_list as $item){
            $this->add($item);
        }
    }

    public function add(mixed $item){
        $this->items[] = $item;
    }

    public function next(){
        $nr_items = count($this->items);

        if($nr_items == 0){
            return NULL;
        }

        $ret_val = $this->items[0];

        $this->items = array_slice($this->items, 1);

        return $ret_val;
    }

    public function isEmpty(){
        return count($this->items) == 0;
    }
}