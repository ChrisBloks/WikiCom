<?php
/*  Allows drawing of ratings
*   Marius 2026
*   ToDo: allow showing of rating count
*/
namespace Wiki\views;

use Wiki\tools\interfaces;

class Rating implements interfaces\iElement
{
    private float $rating;
    private int $roundedrating;
    private int $max;

    public function __construct(float $rating, int $max = 5)
    {
        $this->rating = $rating;
        $this->roundedrating = max(0.0, min($max, round($rating, 0)));
        $this->max = $max;
    }

    public function show(): string
    {
        $str = '<div class="rating_dropdown">
                    <button class="rating_dropdown_button">Rate this page</button>
                    <div class="rating_dropdown_content">
                        <a class="rating_option" value="1" href="#">1</a>
                        <a class="rating_option" value="2" href="#">2</a>
                        <a class="rating_option" value="3" href="#">3</a>
                        <a class="rating_option" value="4" href="#">4</a>
                        <a class="rating_option" value="5" href="#">5</a>
                    </div>
                    <input type="hidden" name="article_id" class="article_id" data_article_id="1" />
                </div>';

        return $str;
        // $percent = ($this->roundedrating / $this->max) * $this->max;
        // $full    = str_repeat('&#9733;', $percent);
        // $empty   = str_repeat('&#9734;', $this->max - $percent);


        // return '<span class="rating-stars" data-rating="' . $this->roundedrating . '">'
        //     . '<span class="stars-full" style="width: ' . $percent . '%;">' . $full . '</span>'
        //     . '<span class="stars-empty">' . $empty . '</span>'
        //     . "($this->rating)"
        //     . '</span>';
    }
}
