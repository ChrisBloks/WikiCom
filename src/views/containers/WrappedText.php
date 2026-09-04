<?php

namespace Wiki\views\containers;

use Wiki\tools\interfaces\iElement;
/**
 * Class to create a text element in a wrapper
 * @var $text text to show on page
 * @var $wrapper the html wrapper for that element
 */
class WrappedText implements iElement
{
    // properties
    protected string $text;
    protected string $wrapper;


    public function __construct(string $text, string $wrapper)
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
