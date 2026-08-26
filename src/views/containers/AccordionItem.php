<?php
namespace Wiki\views\containers;
use Wiki\views\containers\ContainerElement;

class AccordionItem extends ContainerElement {
    public function __construct(string $id, string $label, $innerElement, string $parentId, bool $expanded = false) {
        $showClass = $expanded ? ' show' : '';
        $collapsedClass = $expanded ? '' : ' collapsed';

        $header = '<div class="accordion-item">'
            . '<h2 class="accordion-header">'
            . '<button class="accordion-button' . $collapsedClass . '" type="button" '
            . 'data-bs-toggle="collapse" data-bs-target="#' . $id . '">'
            . $label
            . '</button>'
            . '</h2>'
            . '<div id="' . $id . '" class="accordion-collapse collapse' . $showClass . '" data-bs-parent="#' . $parentId . '">'
            . '<div class="accordion-body">';

        parent::__construct($header, '</div></div></div>');
        $this->addElement($innerElement);
    }
}

// Stub of making accordion element if we want to bbut im not super confident in this class being constructed correctly
                // $accordion = new ContainerElement('<div class="accordion" id="article-accordion-' . $bodyinfo['id'] . '">', '</div>');

                // $accordion->addElement(new AccordionItem(
                //     id: 'summary-' . $bodyinfo['id'],
                //     label: 'Description',
                //     innerElement: new BodyText(text: ucfirst($bodyinfo['summary']), class: $classes['body_class']),
                //     parentId: 'article-accordion-' . $bodyinfo['id'],
                //     expanded: true
                // ));

                // $accordion->addElement(new AccordionItem(
                //     id: 'code-' . $bodyinfo['id'],
                //     label: 'Code',
                //     innerElement: new CodeBlock(text: $bodyinfo['codeBlock'], class: $classes['codeblock_class']),
                //     parentId: 'article-accordion-' . $bodyinfo['id']
                // ));

                // $text_container->addElement(new Title(text: ucfirst($bodyinfo['title']), class: $classes['title_class']));
                // $text_container->addElement(new AuthorText(text: "Author: " . ucfirst($bodyinfo['name']), class: $classes['author_class']));
                // // ToDo: clicking the display tags sends you to search page with tag = link clicked
                // $display_tags = '';
                // foreach ($tags as $key => $value) {
                //     $display_tags .= '<a>' . $value . ' </a>';
                // }
                // $text_container->addElement(new BodyText(
                //     text: $display_tags,
                //     class: ''
                // ));
                // $text_container->addElement($accordion);
                // $outer_container->addElement($text_container);
                // $outer_container->addElement(new Image(
                //     name: './img/article/' . $bodyinfo['imgFileName'],
                //     class: $classes['img_class']
                // ));
                // $main->addElement($outer_container);