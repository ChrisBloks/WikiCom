<?php

namespace Wiki\views;

abstract class HtmlDoc
{

    final public function show(): void
    {
        $this->beginDoc();

        $this->beginHead();
        $this->headContent();
        $this->endHead();

        $this->beginBody();
        $this->bodyContent();
        $this->endBody();

        $this->endDoc();
    }


    private function beginDoc(): void
    {
        echo "<!DOCTYPE html>\n<html>";
    }

    private function beginHead(): void
    {
        echo "<head>";
    }

    abstract protected function headContent(): void;

    private function endHead(): void
    {
        echo '</head>';
    }

    // hardcoded that the body is a flex container, at least as tall as the viewport
    private function beginBody(): void
    {
        echo '<body class="d-flex flex-column min-vh-100">';
    }

    abstract protected function bodyContent(): void;

    private function endBody(): void
    {
        echo '</body>';
    }

    private function endDoc(): void
    {
        echo '</html>';
    }
}
