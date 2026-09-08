<?php

namespace Wiki\views;

/**
 * Builds the basic outline of an HTML doc for a webpage
 */
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

    /**
     * starts doctype
     * @return void 
     */
    private function beginDoc(): void
    {
        echo "<!DOCTYPE html>\n<html>";
    }

    /**
     * start the <head> of a html page
     * @return void 
     */
    private function beginHead(): void
    {
        echo "<head>";
    }

    /**
     * Abstract function to be overwritten by child class
     * @return void
     */
    abstract protected function headContent(): void;

    /**
     * ends the head content
     * @return void
     */
    private function endHead(): void
    {
        echo '</head>';
    }

    // hardcoded that the body is a flex container, at least as tall as the viewport
    private function beginBody(): void
    {
        echo '<body class="d-flex flex-column min-vh-100">';
    }

    /**
     * Abstract function to add the body content of a page
     * To be overwritten by child classes
     * @return void
     */
    abstract protected function bodyContent(): void;

    /**
     * Ends body content
     * @return void
     */
    private function endBody(): void
    {
        echo '</body>';
    }

    /**
     * Ends the html document
     * @return void
     */
    private function endDoc(): void
    {
        echo '</html>';
    }
}
