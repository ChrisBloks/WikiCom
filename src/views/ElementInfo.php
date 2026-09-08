<?php

namespace Wiki\Views;

Class ElementInfo implements \ArrayAccess
{

    public $element_info = [
        "tag" => "",
        "class" => "",
        "id" => "",
        "value" => "",
        "type" => "",
        "closing" => false
    ];

    public function offsetSet($offset, $value): void {
        if ($this->offsetExists($offset)) {
            $this->element_info[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool {
        return isset($this->element_info[$offset]);
    }

    public function offsetUnset($offset): void {
        unset($this->element_info[$offset]);
    }

    public function offsetGet($offset): mixed {
        return isset($this->element_info[$offset]) ? $this->element_info[$offset] : null;
    }
}
