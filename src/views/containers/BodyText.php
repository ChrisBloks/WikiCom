<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;
/**
 * Type of wrapped text specifically for body text
 * @var $text to display html text
 * @var $class addition of class
 */
class BodyText extends WrappedText
{
    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, 'p' . HtmlUtils::addClassAttr($class));
    }
}
