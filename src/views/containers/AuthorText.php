<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;

/**
 * Type of wrapped text specifically for authors
 * @var $text to display html text
 * @var $class addition of class
 */
class AuthorText extends WrappedText
{
    public function __construct(string $text, string $class = '')
    {
        parent::__construct($text, 'h3' . HtmlUtils::addClassAttr($class));
    }
}
