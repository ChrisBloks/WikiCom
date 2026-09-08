<?php

namespace Wiki\dataObjects;

use InvalidArgumentException;

class ElementInfo implements \arrayAccess {

    static private array $allowed_keys =
        [
            'php_class',
            'tag',
            'name',
            'html_class',
            'id',
            'value',
            'type',
            'action',
            'method',
            'style',
            'text',
        ];

    private array $container = [];

    public function __construct(array $attributes) {
        foreach ($attributes as $key => $value){
            $this->container[$key] = $value;
        }
    }

    public function offsetExists(mixed $offset): bool {
        return isset($this->container[$offset]);
    }

    public function offsetGet(mixed $offset): mixed {
        return $this->container[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void {
        if (in_array($offset, $this::$allowed_keys, true)){
            $this->container[$offset] = $value;
        }
        else {
            throw new InvalidArgumentException("{$offset} is not an allowed key of ElementInfo!");
        }
    }

    public function offsetUnset(mixed $offset): void {
        unset($this->container[$offset]);
    }
}