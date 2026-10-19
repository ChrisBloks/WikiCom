<?php

namespace Wiki\views\doc;

class ErrorPage extends HtmlDoc
{
    protected function headContent(): void
    {
        echo '<link rel="stylesheet" href="./src/css/stylesheet.css">';
    }

    protected function beginBody(): void
    {
        echo '<body class="d-flex flex-column min-vh-100 error-page">';
    }
    protected function bodyContent(): void
    {
        echo '<div class="error-page">
        <div id="clouds">
            <div class="cloud x1"></div>
            <div class="cloud x1_5"></div>
            <div class="cloud x2"></div>
            <div class="cloud x3"></div>
            <div class="cloud x4"></div>
            <div class="cloud x5"></div>
        </div>
        <div class="c">
            <div class="_404">404</div>
            <hr>
            <div class="_1">THE PAGE</div>
            <div class="_2">WAS NOT FOUND</div>
            <a class="btn error" href="main.php">BACK TO HOME</a>
        </div></div>';
    }
}