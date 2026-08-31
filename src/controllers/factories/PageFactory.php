<?php
// common parameters:
// Todo: save all commands as string and loop
// is instance of: checken voor interface class
/* values to obtain from outside:
 *$isloggedIn;
 *$page_value;
 *$article_id;
 *$user_ids;
 *$tag_ids;
 *$sortBy;
 */

namespace Wiki\controllers\factories;

use Wiki\tools\utils\HtmlUtils,
    Wiki\tools\traits\tErrorMessageCollector,
    Wiki\tools\exceptions\PageNotFoundException,
    Wiki\models\ModelSelector,
    Wiki\controllers\factories\MenuFactory,
    Wiki\views\BasePage,
    Wiki\views\Table,
    Wiki\views\containers\AtomicElement,
    Wiki\views\containers\Header,
    Wiki\views\containers\BodyText,
    Wiki\views\containers\Title,
    Wiki\views\containers\Image,
    Wiki\views\containers\AuthorText,
    Wiki\views\containers\CodeBlock,
    Wiki\views\containers\Footer,
    Wiki\views\containers\ContainerElement,
    Wiki\views\containers\MainElement,
    Wiki\views\containers\AccordionItem;



class PageFactory
{
    use tErrorMessageCollector;
    private string $page;
    protected bool $isLoggedIn;
    protected array $response;
    private BasePage $htmlpage;
    public function __construct(array $response)
    {
        $this->response = $response;
        $this->page = $response['page'];
        $this->isLoggedIn = $response['isLoggedIn'];
        $this->htmlpage = new BasePage;
    }

    public function show()
    {
        $this->addHead();
        $this->addScripts();

        $this->addBody();

        return $this->htmlpage;
    }


    private function addHead()
    {
        $this->htmlpage->addtoHeadContent(new AtomicElement("<title> Testpage </title>"));
    }

    private function addScripts()
    {
        $this->htmlpage->addToHeadContent(new AtomicElement('
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
                    rel="stylesheet">
                    <link href="./src//css/stylesheet.css" rel="stylesheet">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/styles/default.min.css">
                    '));

        $this->htmlpage->addToHeadContent(new AtomicElement(
            '
                    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/highlight.min.js"></script>
                    <script>hljs.highlightAll();</script>'
        ));
    }


    public function addBody()
    {
        // ToDo: make error element, pass array with errors
        if ($this->hasErrors() == true) {
            HtmlUtils::dump("Errors", $this->getErrors());
        }

        // tNoticeMessage ... eventually

        // title
        $this->htmlpage->addToBodyContent(new Header(
            ucfirst($this->page),
            "fs-1 fw-bold text-center p-3 bg-primary-subtle bg-opacity-10 border border-info"
        ));

        // menu items
        // menu items from database
        // verander createMenu($menu,items, isloggedin) naar true voor de andere  menustructuur
        $menu_items = ModelSelector::getWebsiteInfoModel()->fetchMenuItems($this->isLoggedIn);
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu(
            menu_items: $menu_items,
            class: 'nav bg-body-secondary border-bottom justify-content-around'
        );
        $this->htmlpage->addToBodyContent($menu);
        if ($menuFactory->hasErrors()) {
            foreach ($menuFactory->getErrors() as $error) {
                $this->htmlpage->addToBodyContent(new AtomicElement("- $error <br> ====================<br>"));
            }
        } else {
            $this->htmlpage->addToBodyContent(new AtomicElement('<p '
                . HtmlUtils::addClassAttr("w3-xlarge")
                . '></p>'));
        }

        $main = new MainElement();
        // page building
        switch ($this->page) {
            case 'home':

                $pageinfo = ModelSelector::getWebsiteInfoModel()->fetchBodyText($this->page);
                // get div styling from DB
                $container = new ContainerElement('<div>', '</div>');
                $container->addElement(new BodyText(
                    text: $pageinfo["bodytext"],
                    class: $pageinfo["bodytext_class"]
                ));
                $main->addElement($container);
                break;


            case 'about':
                $aboutinfo = ModelSelector::getWebsiteInfoModel()->fetchAuthorAboutInfo($this->response['aboutID']);

                if ($this->response['userID'] == $this->response['aboutID']) {
                    // Edit view: 
                    $top_container = new ContainerElement('<div class="flex-grow-1">', '</div>');
                    $main_container = new ContainerElement('<div class="d-flex flex-column align-items-center w-75 mx-auto">', '</div>');
                    $sub_container = new ContainerElement('<div>', '</div>');

                    $formFactory = new FormFactory();
                    $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                    $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
                    $form = $formFactory->createForm(
                        form_info: $form_info,
                        field_info: $form_fields,
                        hidden_field_info: [
                            'user' => $this->response['aboutID'],
                            'page' => $this->page
                        ],
                        field_text: ["description" => $aboutinfo["description"]],
                        class: $form_info["display_class"],
                        submit_class: "btn btn-primary btn-sm"
                    );

                    $top_container->addElement(new Title(
                        text: $aboutinfo['name'],
                        class: "fs-1 text-center border-bottom"
                    ));

                    $sub_container->addElement(new Image(
                        name: './img/authors/' . $aboutinfo['imgFileName'],
                        class: $aboutinfo['img_class'] . ' mb-3'
                    ));

                    $main->addElement($top_container);
                    $main_container->addElement($sub_container);
                    $main_container->addElement($form);
                    $main->addElement($main_container);
                } else {
                    // Read-only view
                    $main_container = new ContainerElement('<div class="d-flex align-items-center w-75 mx-auto">', '</div>');
                    $sub_container = new ContainerElement('<div class="flex-grow-1">', '</div>');

                    $sub_container->addElement(new Title(
                        text: $aboutinfo['name'],
                        class: $aboutinfo['name_class']
                    ));
                    $sub_container->addElement(new BodyText(
                        text: $aboutinfo['description'],
                        class: $aboutinfo['description_class']
                    ));

                    $main_container->addElement($sub_container);
                    $main_container->addElement(new Image(
                        name: './img/authors/' . $aboutinfo['imgFileName'],
                        class: $aboutinfo['img_class']
                    ));

                    $main->addElement($main_container);
                }
                break;
            case 'contact':
            case 'login':
            case 'register':
                $container = new ContainerElement('<div>', '</div>');
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: $form_fields,
                    hidden_field_info: ['page' => $this->page],
                    field_text: [],
                    class: $form_info["display_class"]
                );

                $container->addElement($form);
                $main->addElement($container);
                break;
            case 'search':
                $container = new ContainerElement('<div class="d-flex flex-column align-items-center w-75 mx-auto">', '</div>');
                $top_container = new ContainerElement('<div class="d-flex align-items-end border-bottom mb-3 ms-5 me-5">', '</div>');
                $sub_container = new ContainerElement('<div class="ms-5 me-5">', '</div>');

                // start form for collecting filter data
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
                
                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: $form_fields,
                    hidden_field_info: ['page' => $this->page],
                    field_text: [],
                    class: $form_info["display_class"],
                );
                $container->addElement($form);

                $author_ids = $this->response["Author"] ?? [];
                $tag_ids = $this->response["Tag"] ?? [];
                $sortby = $this->response['sortby'] ?? "";

                // create checkbox inputs for filtering
                $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["title", "lastEdit", "rating"]);
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleBySearch(
                    author_ids: $author_ids,
                    tag_ids: $tag_ids,
                    sortBy: $sortby
                );

                // print table for search results
                $tableFactory = new Table($columnsdata, $rowsdata);
                $container->addElement(new AtomicElement($tableFactory->createTable("table
                                                                                                table-hover 
                                                                                                table-striped
                                                                                                table-bordered")));

                // add containers to page
                $main->addElement($container);
                break;
            case 'editArticle':
                $container = new ContainerElement('<div>', '</div>');
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page, $this->response['editArticleID']); //give article tag
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($this->response['editArticleID']);

                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: $form_fields,
                    hidden_field_info: ["articleID" => $this->response['editArticleID'], 'page' => $this->page], //give article tag
                    class: $form_info["display_class"],
                    field_text: $bodyinfo
                );

                // add to page
                $container->addElement($form);
                $main->addElement($container);
                break;

            case 'article':
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($this->response['articleID']);
                $classes = ModelSelector::getWebsiteInfoModel()->fetchClasses($this->page);
                $tags = ModelSelector::getArticleModel()->fetchArticleTags($this->response['articleID']);
                // ToDo: add accordion functionality to body text and code element

                // Outer Div: image + text-div 
                $outer_container = new ContainerElement('<div class="align-items-center w-75 mx-auto">', '</div>');

                // Inner text div: Title/Author/text/code
                $text_container = new ContainerElement('<div class="d-flex flex-grow-1">', '</div>');

                // Top div with title, author, tags and decription title
                $outer_container->addElement(new Title(
                    text: ucfirst($bodyinfo['title']),
                    class: $classes['title_class']
                ));
                $outer_container->addElement(new AuthorText(
                    text: "Author: " . ucfirst($bodyinfo['name']) . "",
                    class: $classes['author_class']
                ));
                $display_tags = '';
                foreach ($tags as $key => $value) {
                    $display_tags .= '<a>' . $value . ' </a>';
                }
                $outer_container->addElement(new BodyText(
                    text: $display_tags,
                    class: 'border-bottom border-top mb-3'
                ));
                $outer_container->addElement(new Title(
                    text: 'Description',
                    class: "h4 mb-4"
                ));

                // Div with body text and image
                $text_container->addElement(new BodyText(
                    text: ucfirst($bodyinfo['summary']),
                    class: $classes['body_class']
                ));
                $text_container->addElement(new Image(
                    name: './img/article/' . $bodyinfo['imgFileName'],
                    class: $classes['img_class']
                ));
                $outer_container->addElement($text_container);


                // bottom div with codeblock
                $bottom_container = new ContainerElement('<div class="align-items-center w-75 mx-auto mt-4">', '</div>');
                $bottom_container->addElement(new Title(
                    text: 'Code',
                    class: "h4"
                ));
                $bottom_container->addElement(new CodeBlock(
                    text: $bodyinfo['codeBlock'],
                    class: $classes['codeblock_class']
                ));

                // add to page
                $main->addElement($outer_container);
                $main->addElement(new ContainerElement('<hr class="w-75 mx-auto my-4">', ''));
                $main->addElement($bottom_container);
                break;

            case 'dashboard':
                // @danny kun je bij dashboard ook de userID meegeven zodat ik niet in session memory hoef te pakken?
                $aboutinfo = ModelSelector::getWebsiteInfoModel()->fetchAuthorAboutInfo($_SESSION['userID']);

                $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["id", "title", "lastEdit"]);

                // add userID to fetcharticlebyUserId
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId(1);

                $main_container = new ContainerElement('<div class="d-flex flex-column align-items-center w-75 mx-auto">', '</div>');
                $top_container = new ContainerElement('<div class="d-flex align-items-end border-bottom mb-3 ms-5 me-5">', '</div>');
                $sub_container = new ContainerElement('<div class="ms-5 me-5">', '</div>');


                $top_container->addElement(new Image(
                        name: './img/authors/' . $aboutinfo['imgFileName'],
                        class: 'dashboard-pic mb-1 justify-content-start rounded ms-5'
                    ));

                $top_container->addElement(new Title(
                    text: $aboutinfo['name'],
                    class: "display-1 text-center ms-5" /* i want the title now */
                ));

                $tableFactory = new Table($columnsdata, $rowsdata);
                $sub_container->addElement(new Title(
                                                text: "Articles",
                                                class: "fs-3"
                ));
                $sub_container->addElement(new AtomicElement($tableFactory->createTable("table
                                                                                                table-hover 
                                                                                                table-striped
                                                                                                table-bordered")));

                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);

                $sub_container->addElement(new Title(
                                                text: "Create new article",
                                                class: "h4 border-top"
                ));

                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: [],
                    hidden_field_info: ['page' => $this->page],
                    class: $form_info["display_class"],
                    field_text: [],
                    submit_class: "btn btn-primary btn-sm"
                );
                $sub_container->addElement($form);

                // Add to page
                $main->addElement($top_container);
                $main->addElement($sub_container);
                break;
            default:
                throw new PageNotFoundException("No page defined for: '. '$this->page.'");
        }

        // end of switch statement
        // add the <main> to the body content
        $this->htmlpage->addToBodyContent($main);

        // add the footer to the body content
        $this->htmlpage->addToBodyContent(new Footer(
            text: 'Christian, Danny, & Marius &copy' . date("Y") . '',
            class: 'border-top text-end bg-primary-subtle mt-auto pe-5'
        ));
    }
}
