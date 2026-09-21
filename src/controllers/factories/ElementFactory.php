<?php

namespace Wiki\controllers\factories;


use Wiki\dataObjects\ElementInfo;
use Wiki\tools\interfaces\iElement;
use Wiki\tools\utils\HtmlUtils;
use Wiki\views\containers\AtomicElement;

class ElementFactory 
{

    private static array $NAMESPACE = [
        'Form' => 'Wiki\views\containers\Form',
        'text' =>  'Wiki\views\fields\InputField',
        'email' => 'Wiki\views\fields\InputField',
        'textarea' => 'Wiki\views\fields\TextAreaField',
        'ButtonField' => 'Wiki\views\fields\ButtonField',
        'ContainerElement' => 'Wiki\views\containers\ContainerElement',
        'AtomicElement' => 'Wiki\views\containers\AtomicElement',
        'Card'  => 'Wiki\views\containers\Card',
        'Title' => 'Wiki\views\containers\Title',
        'BodyText' => 'Wiki\views\containers\BodyText',
        'Image' => 'Wiki\views\containers\Image',
    ];

    public static function createElement(ElementInfo $element_info): iElement {
        HtmlUtils::dump('element_info', $element_info);
        return new self::$NAMESPACE[$element_info['php_class']]($element_info);
    }


}