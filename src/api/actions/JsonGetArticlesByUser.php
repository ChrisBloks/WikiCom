<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
    Wiki\tools\utils\Utils;

class JsonGetArticlesByUser extends BaseJsonAction
{


    protected function _getData(): bool
    {
        $user_id = Utils::getRequestVar('userId', false);
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByUserIdAPI(user_id: $user_id);
        if (!$this->data) {
            return false;
        }
        return true;
    }
}