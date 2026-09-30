<?php
namespace Wiki\views\containers;

use ArrayAccess;
use Wiki\tools\interfaces\iElement;
use Wiki\tools\utils\HtmlUtils;

class Card extends WrappedText
{

    public function __construct(ArrayAccess $element_info)
    {
        parent::__construct($this->createCard(
            image: $element_info['article']['imgFileName'],
            title: $element_info['article']['title'],
            summary: $element_info['article']['summary'],
            article_id: $element_info['article']['id']
        ), 
        'div class ="col"');
    }

    private function createCard(string $image, string $title, string $summary, int $article_id)
    {
        $str = '<div class="card h-100 shadow-sm">';

        if (!empty($image)) {
            $str .= '<img src="' . \Config::ARTICLEIMGPATH . htmlspecialchars($image)
                . '" class="card-img-top" alt="' . htmlspecialchars($title) . '">';
        }

        $str .= '<div class="card-body d-flex flex-column">';
        $str .= '<h5 class="card-title">' . htmlspecialchars($title) . '</h5>';
        $str .= '<p class="card-text text-muted">' . htmlspecialchars($summary) . '</p>';
        $str .= '<a href="?page=article&id=' . $article_id . '" class="btn btn-outline-primary mt-auto">Read Article</a>';
        $str .= '</div>'; 
        $str .= '</div>'; 

        return $str;
    }
}