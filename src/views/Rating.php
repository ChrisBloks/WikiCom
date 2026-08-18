<?php
require_once "./src/tools/interfaces/iElement.php";

class Rating implements iElement
{
    private float $rating;
    private int $max;

    public function __construct(float $rating, int $max = 5)
    {
        $this->rating = max(0.0, min((float)$max, round($rating, 1)));
        $this->max = $max;
    }

    public function show(): string
    {
        $percent = ($this->rating / $this->max) * 100;
        $empty   = str_repeat('&#9734;', $this->max);
        $full    = str_repeat('&#9733;', $this->max);

        return '<span class="rating-stars" data-rating="' . $this->rating . '">'
             . '<span class="stars-empty">' . $empty . '</span>'
             . '<span class="stars-full" style="width: ' . $percent . '%;">' . $full . '</span>'
             . '</span>';
    }
}