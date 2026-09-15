<?php

namespace Wiki\tools\interfaces;

use ArrayAccess;
use Iterator;

interface iElementInfo extends ArrayAccess
{
    public function isEmpty(): bool;
    public function getHTMLAttributes(): array;
}
