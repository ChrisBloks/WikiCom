<?php

namespace Wiki\views\containers;

use Wiki\tools\utils, Wiki\views\fields;

/**
 * Builds a form element with an action and a submit button
 * Hidden fields can be added through Form->addhiddenfield
 * @var $action         URL that processes the form submission
 * @var $method         The HTTP method to submit the form with (POST/GET/etc)
 * @var $submit_caption Text on submit button
 * @var $class          css class styling tag
 * @var $enctype        encoding type
 * @var $submit_class   submit button css class styling
 */
class Form extends ContainerElement
{
    // properties
    protected array $hiddenfields;

    public function __construct(string $action, string $method, string $submit_caption, string $class = "",string $enctype = "", string $submit_class='')
    {
        $this->html_before = '<form action="' . $action . '" method="' . $method . '" ' . utils\HtmlUtils::addClassAttr($class) . 'enctype="'.$enctype.'">';

        $this->html_after = '<button type="submit" value="submit"'. utils\HtmlUtils::addClassAttr($submit_class) .'>' . $submit_caption . ' </button></form>';
    }

}
