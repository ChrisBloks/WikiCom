<?php

namespace Wiki\views\fields;

use Override;
use Wiki\dataObjects\ElementInfo;
use Wiki\tools\interfaces\iElement;
use Wiki\tools\utils\HtmlUtils;

class HiddenField implements iElement
{

    protected string $html;

    public function __construct(ElementInfo $elementInfo)
    {
        $this->html = '<input type="hidden" name="' . $elementInfo['field_info']['field_name'] . '" value="' . $elementInfo['field_info']['value'] . '">' . PHP_EOL;
    }

    public function show(): string
    {
        return $this->html;
    }

    #[Override]
    public function addElement(iElement $element): void
    {
        throw new \Exception('Not implemented');
    }
}
