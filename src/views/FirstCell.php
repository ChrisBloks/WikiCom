<?php

namespace Wiki\views;

use Wiki\tools\utils,
    Wiki\views\containers\Form;
use Wiki\views\fields\HiddenField;
use Wiki\views\containers\DeleteForm;
use Dom\Element;
use Wiki\dataObjects\ElementInfo;
use Wiki\views\fields\ButtonField;
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
        $str .= '<a data-targetpage="page'.ucfirst($this->target_page)
            .'" data-id='.$this->page_id
            .' class="btn btn-primary btn-sm cell-link">'
            . $this->_actionLink($this->page_id, '&#10000;', 'Update')
            . '</a>';
        $str .= $this->_buildDeleteButton();
        $str .= '</div>';
        return $str;
    }

    /**
     * Sends button request to delete an article
     * @return string HTML-string containing the button
     */
    private function _buildDeletebutton(): string
    {
        $button = new ButtonField(
            new ElementInfo([
                'html_tag' => 'button',
                'class' => 'ajax-delete-button btn btn-danger btn-sm delete-button',
                'attributes' => ['data-article-ID' => $this->page_id], 
                'field_info' => ['text' => '&nbsp;'.'&#10005;'.'&nbsp;'], 
            ])
        );
        return $button->show();
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
