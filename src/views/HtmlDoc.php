<?php

abstract class HtmlDoc{

    final public function show(): void{
        $this->beginDoc();

        $this->beginHead();
        $this->headContent();
        $this->endHead();

        $this->beginBody();
        $this->bodyContent();
        $this->endBody();

        $this->endDoc();
    }


    private function beginDoc(): void{
        echo "<!DOCTYPE html>\n<html>";
    }

    private function beginHead(): void{
        echo "<head>";
    }

    abstract protected function headContent(): void;

    private function endHead(): void{
        echo '</head>';
    }

    private function beginBody(): void{
        echo '<body>';
    }

    abstract protected function bodyContent(): void;

    private function endBody(): void{
        echo '</body>';
    }

    private function endDoc(): void{
        echo '</html>';
    }

}