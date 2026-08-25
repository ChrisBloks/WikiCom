<?php
namespace Wiki\views\containers;

use Wiki\views\containers;

// deprecated until i find a better way to add this, use FirstCell instead
class ArticleActions extends ContainerElement
{
    public function __construct(int|string $articleId)
    {
        parent::__construct('', '');

        $id = htmlspecialchars((string)$articleId);

        // Edit: read-only navigation, link is fine.
        $this->addElement(new containers\AtomicElement("<a href=\"edit.php?id=$id\">Edit</a>"));

        // Delete: mutates data, so it must be a POST form, never a link
        $deleteForm = new containers\Form(
            action: "delete.php",
            method: "POST",
            submit_caption: "Delete"
        );
        $deleteForm->addHiddenField("article_id", (string)$articleId);
        $this->addElement($deleteForm); 
    }
}