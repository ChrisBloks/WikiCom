<?php

namespace Wiki\views\containers;

use Wiki\dataObjects\ElementInfo;
use Wiki\dataObjects\FieldInfo;
use Wiki\views\containers\ContainerElement;
use Wiki\views\fields\ButtonField;

class TagButtonContainer extends ContainerElement {


    public function __construct(ElementInfo $element_info)
    {
        parent::__construct($element_info);

        // ASSUMPTION: options_info 0th array is of form [tag_id -> tag_name]
        if(isset($element_info['options_info'])){
            foreach($element_info['options_info'][0] as $tag_id => $tag_name){
                $tagButton = new ButtonField(
                    new ElementInfo([
                        'html_tag' => 'input',
                        'html_class' => 'button button-sm',
                        'element_name' => $tag_id,
                        'field_info' => [
                            'type' => 'button',
                            'value' => $tag_name
                        ],
                        'href' => 'main.php?page=search&tag='.$tag_id // TODO: Get href from controller
                    ])
                );
                $this->addElement($tagButton);
            }
        }
    }
}