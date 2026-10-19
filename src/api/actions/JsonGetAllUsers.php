<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector;
use Wiki\tools\utils\Utils;

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