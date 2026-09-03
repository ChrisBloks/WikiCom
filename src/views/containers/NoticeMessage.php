<?php
/**
 * Creates a bar for display error messages
 */
namespace Wiki\views\containers;

use Wiki\tools\utils\HtmlUtils,
    Wiki\tools\interfaces\iElement;

class NoticeMessage implements iElement
{
    private array $typeClassMap = [
        'error'   => 'alert alert-danger',
        'message' => 'alert alert-success',
    ];

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