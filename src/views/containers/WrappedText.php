<?php

namespace Wiki\views\containers;

use Wiki\tools\interfaces\iElement;
use Wiki\tools\utils\HtmlUtils;

class WrappedText implements iElement
{
    // properties
    protected string $text;
    protected string $wrapper;


    public function __construct($text, $wrapper)
    {
        $this->text = $text;
        $this->wrapper = $wrapper;
    }

    public function show(): string
    {
        return '<' . $this->wrapper . '>' . PHP_EOL
            . $this->text . PHP_EOL
            . '</' . $this->wrapper . '>' . PHP_EOL;
    }
}
