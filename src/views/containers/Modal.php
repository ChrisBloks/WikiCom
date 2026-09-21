<?php
namespace Wiki\views\containers;


/**
 * Modal wraps child elements in a Bootstrap 5 modal structure.
 * Reuses ContainerElement's child-handling via tElementContainer.
 */
class Modal extends ContainerElement
{
    public function __construct(string $id, string $title, string $extra_body_html_before = '')
    {
        $html_before = '<div class="modal fade modal-lg" id="' . htmlspecialchars($id) . '" tabindex="-1" aria-hidden="true">'
            . '<div class="modal-dialog modal-dialog-centered">'
            . '<div class="modal-content">'
            . '<div class="modal-header">'
            . '<h5 class="modal-title">' . htmlspecialchars($title) . '</h5>'
            . '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>'
            . '</div>'
            . '<div class="modal-body">'
            . $extra_body_html_before;

        $html_after = '</div></div></div></div>';

        parent::__construct($html_before, $html_after);
    }
}