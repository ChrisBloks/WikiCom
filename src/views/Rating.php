<?php
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
        $percent = ($this->roundedrating / $this->max) * $this->max;
        $full    = str_repeat('&#9733;', $percent);
        $empty   = str_repeat('&#9734;', $this->max-$percent);


        return '<span class="rating-stars" data-rating="' . $this->roundedrating . '">'
             . '<span class="stars-full" style="width: ' . $percent . '%;">' . $full . '</span>'
             . '<span class="stars-empty">' . $empty . '</span>'
             . "($this->rating)"
             . '</span>';
    }
}