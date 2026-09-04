<?php

namespace Wiki\views;

use Wiki\tools\utils,
    Wiki\views\containers\Form;
use Wiki\views\fields\HiddenField;
/**
 * Creates several options in a table cell that correspond to a set of actions
 * actions are database->delete article or editpage
 * This should usually be the first cell in each table row
 * @var int $page_id | page identifier 
 * @var string $target_page | target page for redirect
 * @var string $delete_page | target for page that needs to be deleted
 */
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

    /**
     * adds options to the first cell of each row: now only href to a page, and a form for delete
     * @return string HTML string containing the div with href and delete form
     */
    public function returnFirstCellOptions(): string
    {
        $editUrl = utils\Url::buildUrl(['page' => $this->target_page, 'id' => $this->page_id]);

        $str = '<div class="d-flex gap-2 align-items-center">';
        $str .= '<a href="' . $editUrl . '" class="btn btn-primary btn-sm">'
            . $this->_actionLink($this->page_id, '&#10000;', 'Update')
            . '</a>';
        $str .= $this->_buildDeleteForm();
        $str .= '</div>';
        return $str;
    }

    /**
     * Sends form request to delete an article
     * @return string HTML-string containing the form
     */
    private function _buildDeleteForm(): string
    {
        $form = new Form(
            action: 'main.php',
            method: 'POST',
            submit_caption: '&#10060;',
            class: "ajax-delete-form",
            submit_class: 'btn btn-danger btn-sm delete-button'
        );
        $form->addElement(new HiddenField(name: 'page', value: $this->delete_page));
        $form->addElement(new HiddenField(name: 'id', value: (string) $this->page_id));
        return $form->show();
    }

    /**
     * Adds the actionlink span to a firstcell action
     * @param string $page_id Page redirect
     * @param string $title Textdisplay
     * @param string $hint handle for ajax handling/targeting if needed
     * @return string
     */
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
