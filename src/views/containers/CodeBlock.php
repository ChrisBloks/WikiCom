<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\dataObjects\ElementInfo;
use Wiki\tools\utils\HtmlUtils;;
/**
 * Type of wrapped text specifically for codeblock
 * @var $text to display html text
 * @var $class addition of class
 */
class CodeBlock extends WrappedText
{
    public function __construct(ElementInfo $element_info)
    {
        parent::__construct($element_info['text'] ?? "", 'code' . HtmlUtils::addClassAttr($element_info['html_class'] ?? ""));
    }

    public function show(): string
    {
        $inner = parent::show();
        $pre = new WrappedText($inner, 'pre');
        return $pre->show();
    }
}
