<?php
namespace Wiki\views\containers;
use Wiki\tools\interfaces\iElementInfo;

/**
 * Modal wraps child elements in a Bootstrap 5 modal structure.
 * Reuses ContainerElement's child-handling via tElementContainer.
 */
class Modal extends ContainerElement
{
    public function __construct(iElementInfo $element_info)
    {
        $this->html_before = '<div class="modal fade modal-lg" id="' . htmlspecialchars($element_info['html_id']) . '" tabindex="-1" aria-hidden="true">'
            . '<div class="modal-dialog modal-dialog-centered">'
            . '<div class="modal-content">'
            . '<div class="modal-header">'
            . '<h5 class="modal-title">' . htmlspecialchars($element_info['text']) . '</h5>'
            . '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>'
            . '</div>'
            . '<div class="modal-body">';

        $this->html_after = '</div></div></div></div>';

    }
}