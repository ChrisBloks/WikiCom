<?php
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils;
use Wiki\views\containers\WrappedText;


class NoticeMessage extends WrappedText
{

    public string $class;

    public function __construct($text, $wrapper, string $class)
    {
        $this->class = $class;
        parent::__construct($this->showErrors(), '');
        unset($_SESSION['errors']);
    }


    private function showErrors(): string
    {
        $str = '';
        foreach ($_SESSION['errors'] as $value) {
            $str .= '<p' . HtmlUtils::addClassAttr($this->class) . '>' . $value . '</p>';
        }

        return $str;
    }

}