<?php

namespace Wiki\views\elements\containers;

use Wiki\tools\dataObjects\ElementInfo;
use Wiki\views\fields\ButtonField;

class TagButtonContainer extends ContainerElement
{


    public function __construct(ElementInfo $element_info)
    {
        parent::__construct($element_info);

        foreach ($element_info['options_info'] as $tag_id => $tag_name) {
            $tagButton = new ButtonField(
                new ElementInfo([
                    'html_tag' => 'input',
                    'html_class' => 'button button-sm',
                    'element_name' => $tag_id,
                    'field_info' => [
                        'type' => 'button',
                        'value' => $tag_name
                    ],
                    'href' => 'main.php?page=search&tag=' . $tag_id // TODO: Get href from controller
                ])
            );
            $this->addElement($tagButton);
        }

    }
}