<?php

namespace Wiki\controllers\factories;


use Wiki\dataObjects\ElementInfo;
use Wiki\tools\interfaces\iElement;
use Wiki\tools\utils\HtmlUtils;
use Wiki\views\containers\AtomicElement;

class ElementFactory 
{

    private static array $NAMESPACE = [
        'form' => 'Wiki\views\containers\Form',
        'input' =>  'Wiki\views\fields\InputField',
        'new_password' => 'Wiki\views\fields\NewPassword',
        'textarea' => 'Wiki\views\fields\TextAreaField',
        'buttonfield' => 'Wiki\views\fields\ButtonField',
        'containerelement' => 'Wiki\views\containers\ContainerElement',
        'atomicelement' => 'Wiki\views\containers\AtomicElement',
        'card'  => 'Wiki\views\containers\Card',
        'title' => 'Wiki\views\containers\Title',
        'bodytext' => 'Wiki\views\containers\BodyText',
        'image' => 'Wiki\views\containers\Image',
        'searchablecheckboxes' =>  'Wiki\views\fields\SearchableCheckboxes',
        'select' => 'Wiki\views\fields\Select',
        'resultstable' => 'Wiki\views\Table',
    ];

    public static function createElement(ElementInfo $element_info): iElement {
        return new self::$NAMESPACE[
            strtolower($element_info['php_class'])
            ]($element_info);
    }


}