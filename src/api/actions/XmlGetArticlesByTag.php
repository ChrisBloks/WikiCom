<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector,
    Wiki\tools\utils\Utils;
use Wiki\tools\utils\HtmlUtils;

class XmlGetArticlesByTag extends BaseXmlAction{
    protected function _getRootName(): string    { return 'wiki-articles'; }
    protected function _getItemName(): string    { return 'article'; }
    protected function _getDownloadName(): string { return 'wiki-articles.xml'; }
    protected function _getCdataFields(): array  { return ['bodytext', 'codeblock']; }
    protected function _getFileFields(): array   { return ['img']; }
    protected function _getFileBaseDir(): string { return './img/article/'; }

    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchArticlesByTagAPI(tag_name:$this->request['tag']);
        if (!$this->data){
            return false;
        }
        return true;
    }
}