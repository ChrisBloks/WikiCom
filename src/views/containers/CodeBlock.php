<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;;

class CodeBlock extends WrappedText
{
    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, 'code' . HtmlUtils::addClassAttr($class));
    }

    public function show(): string
    {
        $inner = parent::show();
        $pre = new WrappedText($inner, 'pre');
        return $pre->show();
    }
}
