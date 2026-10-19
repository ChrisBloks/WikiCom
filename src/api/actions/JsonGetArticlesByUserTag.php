<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
Wiki\tools\utils\Utils;


class JsonGetArticlesByUserTag extends BaseJsonAction{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByTagAndUserIdAPI(tag:$this->request['tag'],user_id:$this->request['userId']);
        if (!$this->data){
            return false;
        }
        return true;
    }
}