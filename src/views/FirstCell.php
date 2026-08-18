<?php

class FirstCell{

    private int $page_id;

    public function __construct(int $page_id){
        $this->page_id = $page_id;
    }


    public function returnFirstCellOptions():string
    {
        $str = '';
        $str .= $this->_actionLink($this->page_id,'&#10000;','Update');
        $str .= $this->_actionLink('-'.$this->page_id, '&#10060;' ,'Delete');
        return $str;
    }


    private function _actionLink(string $page_id, string $title, string $hint) : string
    {
        return '<span'
        . ' class="gw_table_action"'
        . ' id="'.$page_id.'"'
        . ' title="'.$hint.'">'
        . $title
        . '</span>';        
    }    

}