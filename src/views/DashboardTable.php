<?php

namespace Wiki\views;

use Wiki\dataObjects\ElementInfo;
use Override;

class DashboardTable extends Table{

    #
    public function __construct(ElementInfo $element_info)
    {
        $element_info['options_info'][1] = &$element_info['options_info']['articles']; 
        return parent::__construct($element_info);
    }
}