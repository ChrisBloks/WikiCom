<?php

namespace Wiki\views\containers;

use Override;
use Wiki\dataObjects\ElementInfo;
use Wiki\tools\interfaces\iElement;
use Wiki\tools\utils\HtmlUtils;
Use Wiki\tools\utils\Utils;

class EditableArticle extends Form {

    public int $article_id;
    public string $article_title;
    public string $article_body;

    public function __construct(ElementInfo $element_info)
    {
        
        foreach($element_info['sub_fields'] as $sub_field){
            // Add title 
            if($sub_field['field_info']['field_name'] == 'title'){
                $sub_field['field_info']['value'] = $element_info['article_info']['title'];
            }

            // Add summary
            if($sub_field['field_info']['field_name'] == 'bodytext'){
                $sub_field['field_info']['text'] = $element_info['article_info']['summary'];
            }

            // Add codeblock
            if($sub_field['field_info']['field_name'] == 'codeblock'){
                $sub_field['field_info']['text'] = $element_info['article_info']['codeBlock'];
            }
        }

        // Add tags
        HtmlUtils::dump('element_info', $element_info);
        $tags = $element_info['article_info']['tags'];
        $checkbox_group = Utils::get_array_element_where(
            arr: $element_info['sub_fields'],
            fn: fn($element) => $element['element_name'] == 'article_field_tags');
        foreach($checkbox_group['options_info'][0] as &$checkbox){
            if (in_array($checkbox['name'], $tags)){
                $checkbox['checked'] = True;
            }
        }

        
 

        parent::__construct($element_info);
    }

}