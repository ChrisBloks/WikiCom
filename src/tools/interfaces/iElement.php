<?php

namespace Wiki\tools\interfaces;

interface iElement
{

    public function show(): string;
    public function addElement(iElement $element): void; // Necessary to avoid IDE-thrown error in pagefactory
}
