<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
    Wiki\tools\utils\Utils;

class JsonGetArticlesByUser extends BaseJsonAction
{


    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByUserIdAPI(user_id: $this->request['userId']);
        if (!$this->data) {
            return false;
        }
        return true;
    }
}