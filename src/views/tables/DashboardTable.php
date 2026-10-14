<?php

namespace Wiki\views\tables;


use Override;
use Wiki\tools\dataObjects\ElementInfo;

class DashboardTable extends Table{

    #
    public function __construct(ElementInfo $element_info)
    {
        $element_info['options_info'][1] = &$element_info['options_info']['articles']; 
        return parent::__construct($element_info);
    }
}