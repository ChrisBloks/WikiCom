<?php
// creates body text
namespace Wiki\views\containers;

use ArrayAccess;
use Nette\Utils\Html;
use Wiki\tools\utils\HtmlUtils;
/**
 * Type of wrapped text specifically for images
 * @var $text to display html text
 * @var $class addition of class
 */
class Image extends WrappedText
{
    public function __construct(ArrayAccess $element_info)
    {
        parent::__construct('', 'img src=' . \CONFIG::AUTHORIMGPATH.$element_info['image'] . '' . HtmlUtils::addClassAttr($element_info['html_class']));
    }
}
