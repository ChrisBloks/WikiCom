<?php
require_once "./src/views/containers/ContainerElement.php";
require_once "./src/views/containers/AtomicElement.php";
require_once "./src/views/containers/Form.php";

class ArticleActions extends ContainerElement
{
    public function __construct(int|string $articleId)
    {
        parent::__construct('', '');

        $id = htmlspecialchars((string)$articleId);

        // Edit: read-only navigation, link is fine.
        $this->addElement(new AtomicElement("<a href=\"edit.php?id=$id\">Edit</a>"));

        // Delete: mutates data, so it must be a POST form, never a link
        $deleteForm = new Form(
            action: "delete.php",
            method: "POST",
            submit_caption: "Delete"
        );
        $deleteForm->addHiddenField("article_id", (string)$articleId);
        $this->addElement($deleteForm); 
    }
}