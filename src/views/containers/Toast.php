<?php

namespace Wiki\views\containers;

use Override;
use Wiki\dataObjects\ElementInfo;
use Wiki\tools\utils\HtmlUtils,
    Wiki\tools\interfaces\iElement;

/**
 * Creates an element used for displaying notice messages
 */
class Toast implements iElement
{

    protected ElementInfo $element_info;

    public function __construct(ElementInfo $element_info)
    {
        $this->element_info = $element_info;
    }


    /** stores all notice messages based on type
     * in a string with class attribute
     */
    public function show(): string
    {
        $notices = $this->collectNotices();

        $str = '';
        // stacking container
        $str .= '<div class="' . $this->element_info['html_class'] . '" id="' . $this->element_info['html_id'] . '">';

        foreach ($notices as $notice) {

            $bg_colour = match ($notice['type']) {
                'error' => 'text-bg-danger',
                'message' => 'text-bg-success'
            };
            // build toast element
            $str .= '<div class="toast ' . htmlspecialchars($bg_colour) . '" role="alert" aria-live="assertive" aria-atomic="true">';
            $str .= '<div class="toast-header">';
            $str .= '<strong class="me-auto">' . htmlspecialchars(ucfirst($notice["type"])) . '</strong>';
            $str .= '<small class="toast-timestamp" data-time="' . (time() * 1000) . '">just now</small>';
            $str .= '<button type="button" class="btn-close toast-button" data-bs-dismiss="toast" aria-label="Close"></button>';
            $str .= '</div>';
            $str .= '<div class="toast-body">' . htmlspecialchars($notice['text']) . '</div>';
            $str .= '</div>';
        }

        $str .= '</div></div>';

        unset($_SESSION['errors'], $_SESSION['messages']);

        return $str;
    }

    /** collects all messages from session and assigns message type to them
     * 
     */
    private function collectNotices(): array
    {
        $notices = [];

        foreach ($_SESSION['errors'] ?? [] as $key => $error) {
            if (is_int($key)) {
                $notices[] = ['type' => 'error', 'text' => $error];
            }
        }

        foreach ($_SESSION['messages'] ?? [] as $message) {
            $notices[] = ['type' => 'message', 'text' => $message];
        }

        return $notices;
    }
    public function addElement(iElement $element): void
    {
        throw new \Exception('Not implemented');
    }
}
