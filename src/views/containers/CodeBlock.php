<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;;
/**
 * Type of wrapped text specifically for codeblock
 * @var $text to display html text
 * @var $class addition of class
 */
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
