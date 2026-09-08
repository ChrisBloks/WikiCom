<?php
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils,
    Wiki\tools\interfaces\iElement;

/**
 * Creates an element used for displaying notice messages
 */
class NoticeMessage implements iElement
{
    // moveto DB
    private array $typeClassMap = [
        'error'   => 'alert alert-danger',
        'message' => 'alert alert-success',
    ];

    /** stores all notice messages based on type
     * in a string with class attribute
    */ 
    public function show(): string
    {
        $notices = $this->collectNotices();

        if (empty($notices)) {
            return '';
        }

        $str = '';
        foreach ($notices as $notice) {
            $class = $this->typeClassMap[$notice['type']];
            $str .= '<p' . HtmlUtils::addClassAttr($class) . '>'
                . htmlspecialchars($notice['text'])
                . '</p>';
        }

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
}