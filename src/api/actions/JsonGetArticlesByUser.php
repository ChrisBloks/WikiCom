<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
Wiki\tools\utils\Utils;

class JsonGetArticlesByUser extends BaseJsonAction
{


    protected function _getData(): bool
    {
        $user_id = Utils::getRequestNumber('userId', false);
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByUserID(user_id: $user_id);
        if (!$this->data) {
            return false;
        }
        return true;
    }
}