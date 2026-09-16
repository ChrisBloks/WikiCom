<?php

namespace Wiki\dataObjects;

use InvalidArgumentException;
use Override;
use Throwable;
use Wiki\tools\interfaces\iElementInfo;
use Wiki\tools\utils\HtmlUtils;

class ElementInfo implements iElementInfo
{
    static private array $allowed_keys =
    [
        'html_tag',
        'html_class',
        'id',
        'name',
        'text',
        'js_class',
        'php_class',
        'order_by',
        'parent_order',
        'closing_tag',
        'article',
        'title',
        'bodytext',
        'image',
        'element_id'
    ];

    private array $container = [];

    public function __construct(array $attributes = [], bool $permissive = false)
    {
        foreach ($attributes as $key => $value) {
            try {
                $this[$key] = $value;
            } catch (InvalidArgumentException $e) {
                if (!$permissive) {
                    throw $e;
                }
                continue;
            }
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if ($offset == 'class') $offset = 'html_class';
        return $this->container[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset == 'class') $offset = 'html_class';
        if (in_array($offset, static::$allowed_keys, true)) {
            $this->container[$offset] = $value;
        } else {
            throw new InvalidArgumentException("{$offset} is not an allowed key of {$this}!");
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
    }

    public function isEmpty(): bool
    {
        return empty($this->container);
    }

    public function getHTMLAttributes(): array {
        return ['class', 'id', 'name'];
    }

    #[Override]
    public function __toString(): string
    {
        
        $get_class = (function () {
            $class_name = get_class($this);
            if ($pos = strrpos($class_name, '\\')) return substr($class_name, $pos + 1);
            return $pos;
        });

        $s = "{$get_class()}:[";
        foreach($this->container as $key => $value){
            $s .= "{$key} => {$value}, ";
        }
        $s .= "]";
        return $s;
    }
}
