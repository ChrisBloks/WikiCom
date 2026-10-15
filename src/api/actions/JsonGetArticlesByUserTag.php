<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
Wiki\tools\utils\Utils;


class JsonGetArticlesByUserTag extends BaseJsonAction{


    protected function _getData(): bool
    {
        $user_id = Utils::getRequestVar('userId', false);
        $tag = Utils::getRequestVar('tag', false);
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByTagAndUserId(tag:$tag,user_id:$user_id);
        if (!$this->data){
            return false;
        }
        return true;
    }
}