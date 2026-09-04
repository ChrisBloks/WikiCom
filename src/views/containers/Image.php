<?php
// creates body text
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;
/**
 * Type of wrapped text specifically for images
 * @var $text to display html text
 * @var $class addition of class
 */
class Image extends WrappedText
{
    public function __construct(string $name, string $class = '',)
    {
        parent::__construct('', 'img src=' . $name . '' . HtmlUtils::addClassAttr($class));
    }
}
