<?php

class FirstCell
{

    private int $page_id;
    public function __construct(int $page_id)
    {
        $this->page_id = $page_id;
    }


    public function returnFirstCellOptions(): string
    {
        $str = '';
        $str .= '<a href="?page=editArticle&id=' . $this->page_id . '">' . $this->_actionLink($this->page_id, '&#10000;', 'Update') . '</a>';
        $str .= $this->_actionLink('-' . $this->page_id, '&#10060;', 'Delete');
        return $str;
    }


    private function _actionLink(string $page_id, string $title, string $hint): string
    {
        return '<span'
            . ' class="wiki-table-action"'
            . ' id="' . $page_id . '"'
            . ' title="' . $hint . '">'
            . $title
            . '</span>';
    }

}