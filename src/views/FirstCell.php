<?php
namespace Wiki\views;

use Wiki\tools\utils;
class FirstCell
{
    private int $page_id;
    private string $target_page;
    private string $delete_page;

    public function __construct(int $page_id, string $target_page, string $delete_page)
    {
        $this->page_id     = $page_id;
        $this->target_page = $target_page;
        $this->delete_page = $delete_page;
    }

    // adds options to the first cell of each row: now only href to a page, and a form for delete
    public function returnFirstCellOptions(): string
    {

        $editUrl = utils\Url::buildUrl(['page' => $this->target_page, 'id' => $this->page_id]);

        $str  = '<a href="' . $editUrl . '">' 
                . $this->_actionLink($this->page_id, '&#10000;', 'Update') 
                . '</a>';
        $str .= $this->_buildDeleteForm();
        return $str;
    }

    // replace with get-request later on/ajax
    private function _buildDeleteForm(): string
    {
        $form = new containers\Form(
                    action: '', 
                    method: 'POST', 
                    submit_caption: '&#10060;'
                    );
        $form->addHiddenField('page', $this->delete_page);
        $form->addHiddenField('id', (string) $this->page_id);
        return $form->show();
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