<?php

namespace Wiki\views\containers;

use Wiki\controllers\factories\ElementFactory;
use Wiki\dataObjects\ElementInfo;
use Wiki\dataObjects\FieldInfo;
use Wiki\dataObjects\FormInfo;
use Wiki\tools\interfaces\iElementInfo;
use Wiki\tools\utils, Wiki\views\fields;
use Wiki\tools\utils\HtmlUtils;

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

    public function __construct(ElementInfo $element_info)
    {
        // HtmlUtils::dump('contact_form element info', $element_info);
        $form_info = $element_info['form_info'];

        // Build opening tag
        $this->html_before = "<{$element_info['html_tag']}";
        // Add standard HTML attributes
        foreach ($element_info->getHTMLattributes() as $attr) {
            $this->html_before .=  ($element_info[$attr] ? $attr . '="' . $element_info[$attr] . '" ' : "");
        }
        // Add form HTML attributes
        $this->html_before .= 'method="'.$form_info['method'].'"';
        $this->html_before .= ">" . ($element_info['text'] ?? "");
        
        // Build closing tag
        $this->html_after = ($element_info['closing_tag'] !== false ? "</{$element_info['html_tag']}>" : "");

        // Add all fields
        foreach($element_info['sub_fields'] as $sub_element_info){
            $sub_element_info = new ElementInfo($sub_element_info);
            $sub_element = ElementFactory::createElement($sub_element_info);
            $this->addElement($sub_element);
        }

        // Add button
        $button = ElementFactory::createElement(
            new ElementInfo([
                'html_tag' => 'button',
                'php_class' => 'ButtonField',
                'html_class' => $form_info['submit_class'],
                'field_info' => new FieldInfo([
                    'type' => 'submit',
                    'value' => 'submit',
                    'text' => '<span class="spinner-border spinner-border-sm d-none" role="status"></span>' . $form_info['submit_caption']
                ])
            ])
        );
        $this->addElement($button);

    }

}
