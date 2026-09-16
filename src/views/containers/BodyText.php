<?php
// creates body text
namespace Wiki\views\containers;

use ArrayAccess;
use Wiki\tools\utils\HtmlUtils;
/**
 * Type of wrapped text specifically for body text
 * @var $text to display html text
 * @var $class addition of class
 */
class BodyText extends WrappedText
{
    public function __construct(ArrayAccess $element_info)
    {
        parent::__construct($element_info['bodytext'], $element_info['html_tag'] . HtmlUtils::addClassAttr($element_info['html_class']));
    }
}
