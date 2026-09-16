<?php

namespace Wiki\views\containers;

use Wiki\dataObjects\ElementInfo;
use Wiki\dataObjects\LinkedElementInfo;
use Wiki\tools\utils\HtmlUtils;

/**
 * Draws one menu item
 * @var string $label       Adds the menu display tex
 * @var string $href        Add href tag
 * @var string $class       Add css styling for item
 * @var array $attrs        Add attributes from array
 * @var string $li_class    Add css styling for the list element
 */
class Menuitem extends ContainerElement
{
    public function __construct(string $label, string $href, string $class = '', ?array $attrs = null, string $li_class = '')
    {
        $element_info = new LinkedElementInfo([
                'html_tag' => 'li',
                'class' => $li_class,
            ]);
        foreach($attrs as $attr => $val){
            $element_info[$attr] = $val;
        }

        parent::__construct($element_info);

        $this->addElement(
            new AtomicElement(
                new LinkedElementInfo([
                    'html_tag' => 'a',
                    'href' => htmlspecialchars($href),
                    'class' => $class,
                    'label' => htmlspecialchars($label)
                ])
            )
        );


    }
}
