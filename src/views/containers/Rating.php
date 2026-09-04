<?php
/*  Allows drawing of ratings
*   Marius 2026
*   ToDo: allow showing of rating count
*/
namespace Wiki\views\containers;

use Wiki\views\containers\ContainerElement,
    Wiki\views\fields\Select;
use Wiki\views\fields\ButtonField;
use Wiki\views\fields\HiddenField;

/**
 * Add an element containing user rating
 * @var float $rating       the rating in numbers
 * @var int $article_id     the id of the page its linked to
 * @var bool $display_only  Wether you can rate it or not
 * @var bool $isLoggedIn    Whether the user is logged in
 */
class Rating extends ContainerElement
{

    public function __construct(float $rating, int $article_id, bool $display_only = false,bool $isloggedIn = false)
    {
        parent::__construct('<div class="rating_div">', '</div>');

        $max = 5;
        $roundedrating = max(0.0, min($max, round($rating, 0)));
        $percent = ($roundedrating / $max) * $max;
        $full    = str_repeat('&#9733;', $percent);
        $empty   = str_repeat('&#9734;', $max - $percent);

        // Add interactive element
        if (!$display_only && $isloggedIn){
            // Add dropdown
            $this->addElement(
                new Select(
                    name: "rating_dropdown_".$article_id,
                    label: "Rate this article",
                    class: "rating_select",
                    options: [1 => 1,
                            2 => 2,
                            3 => 3,
                            4 => 4,
                            5 => 5],
                    option_class:"rating_option"
                )
            );

            // Add button
            $this->addElement(
                new ButtonField(
                    type: "button",
                    name: "rating_button",
                    class: "rating_button",
                    label: 'Submit rating'
                )
            );
        }

        // Add display element
        $this->addElement(
            new AtomicElement(
                html: '<p class="article_rating_display"><span class="stars-full" style="width: ' . $percent . '%;">' . $full 
                . '</span><span class="stars-empty">' . $empty . '</span>
                 ('. round($rating, 1) . ') </p>'
            )
        );

        // Add hidden element
        $this->addElement(
            new HiddenField(
                name: 'article_id',
                value: $article_id
            )
        );
    }
}

