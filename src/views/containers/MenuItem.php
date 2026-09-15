<?php

namespace Wiki\views\containers;

use Wiki\dataObjects\ElementInfo;
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
    public function __construct(string $label, string $href, string $class = '', array $attrs = [], string $li_class = '')
    {
        HtmlUtils::dump('attrs', $attrs);
        $safe_label = htmlspecialchars($label);
        $safe_href = htmlspecialchars($href);


        $element_info_inner = ["html_tag" => 'a href="?page=' . $safe_href . '" '. HtmlUtils::addAttrs($attrs) .'',"html_class" => $class];

        $element_inner = new ContainerElement(new ElementInfo($element_info_inner));

        $element_inner->addElement(new AtomicElement($safe_label));


        HtmlUtils::dump("flag???",htmlentities($element_inner->show()));

        parent::__construct(new ElementInfo(["html_tag" => 'li '.$element_inner->show(),"html_class" => $li_class]));
    }


    public function __construct2(string $label, string $href, string $class = '', array $attrs = [], string $li_class = '')
    {
        $safe_label = htmlspecialchars($label);
        $safe_href = htmlspecialchars($href);

        parent::__construct(
            new ElementInfo([
                'html_tag' => 'li',
                'class' => $li_class,
            ])
        );

        $this->addElement(
            new AtomicElement(
                new ElementInfo([
                    'html_tag' => 'a',
                    'href' => $safe_href,
                    'class' => $class,
                    'label' => $safe_label
                ])
            )
        );
    }
}
