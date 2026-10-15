<?php

namespace Wiki\api\actions;
use wiki\models\ModelSelector;

class JsonGetAllTags extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchAllTags();
        if (!$this->data){
            return false;
        }
        return true;
    }
}