<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector;

class XmlGetAllTags extends BaseXmlAction{
    protected function _getRootName(): string    { return 'wiki-tags'; }
    protected function _getItemName(): string    { return 'tags'; }
    protected function _getDownloadName(): string { return 'wiki-tags.xml'; }
    protected function _getCdataFields(): array  { return []; }
    protected function _getFileFields(): array   { return ['img']; }
    protected function _getFileBaseDir(): string { return './img/article/'; }

    protected function _getData(): bool
    {
        $this->data = ModelSelector::getArticleModel()->fetchAllTags();
        if (!$this->data){
            return false;
        }
        return true;
    }
}