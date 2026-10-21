<?php

namespace Wiki\api\actions;
use Wiki\models\ModelSelector;
use Wiki\tools\utils\Utils;

class XmlGetAllUsers extends BaseXmlAction{
    protected function _getRootName(): string    { return 'wiki-authors'; }
    protected function _getItemName(): string    { return 'authors'; }
    protected function _getDownloadName(): string { return 'wiki-authors.xml'; }
    protected function _getCdataFields(): array  { return []; }
    protected function _getFileFields(): array   { return ['img']; }
    protected function _getFileBaseDir(): string { return './img/article/'; }
    protected function _getData(): bool
    {
        $this->data = ModelSelector::getUserInfoModel()->fetchAllUsers();
        if (!$this->data){
            return false;
        }
        return true;
    }
}