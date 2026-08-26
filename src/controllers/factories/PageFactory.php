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
Wiki\views\containers\MainElement;



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
                . '><br></p>'));
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
                $container = new ContainerElement('<div>', '</div>');
                if ($this->response['userID'] == $this->response['aboutID']) // author equals user
                {
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
                        class: $form_info["display_class"]
                    );
                    $container->addElement(new Title(text: $aboutinfo['name']));
                    $container->addElement($form);
                    $main->addElement($container);
                } else {
                    $container->addElement(new Image(
                        name: './img/authors/' . $aboutinfo['imgFileName'],
                        class: $aboutinfo['img_class']
                    ));
                    $container->addElement(new Title(
                        text: $aboutinfo['name'],
                        class: $aboutinfo['name_class']
                    ));
                    $container->addElement(new BodyText(
                        text: $aboutinfo['description'],
                        class: $aboutinfo['description_class']
                    ));

                    $main->addElement($container);
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

                $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["title", "lastEdit", "rating"]);
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleBySearch(
                    author_ids: [],
                    tag_ids:    []);
                $tableFactory = new Table($columnsdata, $rowsdata);
                $container->addElement(new AtomicElement($tableFactory->createTable("table
                                                                                                table-hover 
                                                                                                table-striped
                                                                                                table-bordered")));
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

                $container->addElement($form);
                $main->addElement($container);
                break;
            case 'article':
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($this->response['articleID']);
                $classes = ModelSelector::getWebsiteInfoModel()->fetchClasses($this->page);
                // ToDo: add accordion functionality to body text and code element
                $container = new ContainerElement("<div>", "</div>");
                $container->addElement(new Title(
                    text: ucfirst($bodyinfo['title']),
                    class: $classes['title_class']
                ));
                $container->addElement(new AuthorText(
                    text: "Author: " . ucfirst($bodyinfo['name']) . "",
                    class: $classes['author_class']
                ));
                $container->addElement(new BodyText(
                    text: "<p1>" . ucfirst($bodyinfo['summary']) . "</p>",
                    class: $classes['body_class']
                ));
                $container->addElement(new CodeBlock(
                    text: $bodyinfo['codeBlock'],
                    class: $classes['codeblock_class']
                ));
                $container->addElement(new Image(
                    name: './img/article/' . $bodyinfo['imgFileName'],
                    class: $classes['img_class']
                ));

                $main->addElement($container);
                break;
            case 'dashboard':
                $container = new ContainerElement("<div>", "</div>");
                
                $container->addElement(new Title("Articles:"));
                $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["id", "title", "lastEdit"]);
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId(1);
                $tableFactory = new Table($columnsdata, $rowsdata);
                $container->addElement(new AtomicElement($tableFactory->createTable("table
                                                                                                table-hover 
                                                                                                table-striped
                                                                                                table-bordered")));

                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);


                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: [],
                    hidden_field_info: ['page' => $this->page],
                    class: $form_info["display_class"],
                    field_text: []
                );

                $container->addElement($form);
                $main->addElement($container);
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
            class: 'border-top text-end bg-primary-subtle mt-auto'
        ));
    }
}
