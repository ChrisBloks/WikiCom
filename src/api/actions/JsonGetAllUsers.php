<?php

namespace Wiki\api\actions;
use wiki\models\ModelSelector;

class JsonGetAllUsers extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getUserInfoModel()->fetchAllUsers();
        if (!$this->data){
            return false;
        }
        return true;
    }
}