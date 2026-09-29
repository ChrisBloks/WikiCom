<?php
/*  Allows drawing of ratings
 *   Marius 2026
 *   ToDo: allow showing of rating count
 */

namespace Wiki\views\containers;

use Wiki\dataObjects\ElementInfo;
use Wiki\dataObjects\FieldInfo;
use Wiki\views\containers\ContainerElement,
    Wiki\views\fields\Select;
use Wiki\views\fields\ButtonField;
use Wiki\views\fields\HiddenField;
use Wiki\tools\utils\HtmlUtils;

/**
 * Add an element containing user rating
 * @var float $rating       the rating in numbers
 * @var int $article_id     the id of the page its linked to
 * @var bool $display_only  Wether you can rate it or not
 * @var bool $isLoggedIn    Whether the user is logged in
 */
class Rating extends ContainerElement
{

    public function __construct(ElementInfo $element_info)
    {
        parent::__construct($element_info);

        $rating = $element_info['options_info']['rating'] ?? 0;
        $display_only = $element_info['options_info']['display_only'] ?? true;
        $ratable = $element_info['options_info']['ratable'] ?? true;
        $article_id = $element_info['options_info']['article_id'] ?? -1;
        $count = $element_info['options_info']['count'] ?? '?';


        $max = 5;
        $percent = ($rating / $max) * 100;


        // Add interactive element
        if (!$display_only && $ratable) {
            // Add dropdown
            $this->addElement(
                new Select(
                    new ElementInfo([
                    'element_name' => "rating_dropdown_" . $article_id,
                    'html_class' => "rating_select",
                    'field_info' => new FieldInfo([
                        'label' => "Rate this article"
                    ]),
                    'options' => [
                        [
                            1 => 1,
                            2 => 2,
                            3 => 3,
                            4 => 4,
                            5 => 5
                        ],
                        'rating_option'
                    ]
                ]))
            );

            // Add button
            $this->addElement(
                new ButtonField(
                    new ElementInfo([
                        'element_name' => "rating_button",
                        'html_class' => "rating_button",
                        'field_info' => new FieldInfo([
                            'type' => "button",
                            'label' => 'Submit rating'
                        ])
                    ])
                )
            );
        }

        // Add display element
        $this->addElement(
            new AtomicElement(
                new ElementInfo([
                    'text' => '<div class="star-ratings">
                            <div class="fill-ratings" style="width: ' . $percent . '%;">
                                <span>★★★★★</span>
                            </div>
                            <div class="empty-ratings">
                                <span>★★★★★</span> 
                            </div>
                            <div class="count-rating">
                            (' . $count . ')
                            </div>
                            </div>'
                ])
            )
        );

        // Add hidden element
        $this->addElement(
            new HiddenField(
                new ElementInfo([
                    'element_name' => 'article_id',
                    'field_info' => new FieldInfo([
                        'value' => $article_id
                    ])
                ]) 
            )
        );
    }
}
