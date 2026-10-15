<?php

namespace Wiki\system\factories;

use Wiki\tools\dataObjects\ElementInfo;
use Wiki\tools\interfaces\iElement;

class ElementFactory 
{

    private static array $NAMESPACE = [
        'form' => 'Wiki\views\elements\containers\Form',
        'input' =>  'Wiki\views\fields\InputField',
        'new_password' => 'Wiki\views\fields\NewPassword',
        'textarea' => 'Wiki\views\fields\TextAreaField',
        'buttonfield' => 'Wiki\views\fields\ButtonField',
        'containerelement' => 'Wiki\views\elements\containers\ContainerElement',
        'atomicelement' => 'Wiki\views\elements\atomics\AtomicElement',
        'card'  => 'Wiki\views\elements\atomics\Card',
        'title' => 'Wiki\views\elements\atomics\Title',
        'bodytext' => 'Wiki\views\elements\atomics\BodyText',
        'image' => 'Wiki\views\elements\atomics\Image',
        'searchablecheckboxes' =>  'Wiki\views\fields\SearchableCheckboxes',
        'select' => 'Wiki\views\fields\Select',
        'resultstable' => 'Wiki\views\tables\Table',
        'dashboardtable' => 'Wiki\views\tables\DashboardTable',
        'dialoguebutton' => 'Wiki\views\fields\DialogueButton',
        'modal' => 'Wiki\views\elements\containers\Modal',
        'hiddenfield' => 'Wiki\views\fields\HiddenField',
        'editablearticle' => 'Wiki\views\elements\containers\EditableArticle',
        'textareafield' => 'Wiki\views\fields\TextAreaField',
        'toast' => 'Wiki\views\elements\atomics\Toast',
        'rating' => 'Wiki\views\elements\containers\Rating',
        'tagbuttoncontainer' => 'Wiki\views\elements\containers\TagButtonContainer',
        'codeblock' => 'Wiki\views\elements\atomics\CodeBlock',
        'button' => 'Wiki\views\fields\Buttonfield',
    ];

    public static function createElement(ElementInfo $element_info): iElement {
        // HtmlUtils::dump('Creating Element', $element_info);
        return new self::$NAMESPACE[
            strtolower($element_info['php_class'])
            ]($element_info);
    }


}