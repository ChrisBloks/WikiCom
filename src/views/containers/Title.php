<?php
namespace Wiki\views\containers;

use ArrayAccess;
use Wiki\tools\utils\HtmlUtils;

/**
 * Type of wrapped text specifically for a page title
 * @var $text to display html text
 * @var $class addition of class
 */
class Title extends WrappedText
{
    public function __construct(ArrayAccess $element_info)
    {
        parent::__construct($element_info['title'], $element_info['html_tag'] . htmlutils::addClassAttr($element_info['html_class']));
    }
}
