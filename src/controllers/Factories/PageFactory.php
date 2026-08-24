<?php
// common parameters:
// Todo: save all commands as string and loop
// is instance of: checken voor interface class
require_once("./src/models/ModelSelector.php");
require_once("./src/tools/traits/tErrorMessageCollector.php");
/* values to obtain from outside:
 *$isloggedIn;
 *$page_value;
 *$article_id;
 *$user_ids;
 *$tag_ids;
 *$sortBy;
 */
class PageFactory
{
    use tErrorMessageCollector;
    private string $page;
    protected bool $isLoggedIn;
    private BasePage $htmlpage;
    public function __construct(array $response)
    {
        $this->page = $response['page'];
        $this->isLoggedIn = $response['isLoggedIn'];
        $this->htmlpage = new Basepage;
        // check user loginstatus
        // if (isset($_SESSION['userName'])) {
        //     $this->isLoggedIn = true;
        // } else {
        //     $this->isLoggedIn = false;
        // }
    }

    public function show()
    {
        $this->addHead();
        $this->addScripts();

        $this->addBody();
        $this->addFooter();

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
                    rel="stylesheet">'));
        $this->htmlpage->addToHeadContent(new AtomicElement('
                    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/styles/default.min.css">
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
        $this->htmlpage->addToBodyContent(new Header(ucfirst($this->page) , 
                                                "fs-1 fw-bold text-center p-3 
                                                bg-primary-subtle bg-opacity-10 
                                                border border-info"));

        // menu items
        // menu items from database
        // verander createMenu($menu,items, isloggedin) naar true voor de andere  menustructuur
        $menu_items = ModelSelector::getWebsiteInfoModel()->getMenuItems($this->isLoggedIn);
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu(
                                    menu_items: $menu_items, 
                                    class: 'nav bg-body-secondary border-bottom justify-content-around');
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

        // page building
        switch ($this->page) {
            case 'home':
                $pageinfo = ModelSelector::getWebsiteInfoModel()->getBodyText($this->page);
                $this->htmlpage->addToBodyContent(new BodyText(
                                                text: $pageinfo["bodytext"],
                                                class: $pageinfo["bodytext_class"]));
                break;
            case 'about':
                $aboutinfo = ModelSelector::getWebsiteInfoModel()->getAuthorAboutInfo($_GET["author"]);

                if ($this->isLoggedIn === true) // author equals user
                {
                    $formFactory = new FormFactory();
                    $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page);
                    $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                    $form = $formFactory->createForm(form_info: $form_info, 
                                                     field_info: $form_fields, 
                                                     hidden_field_info: ["user" => $_GET["author"]], 
                                                     field_text: ["description" => $aboutinfo["description"]],
                                                     class: $form_info["display_class"]
                                                     );
                    $this->htmlpage->addToBodyContent(new Title(text:$aboutinfo['name']));
                    $this->htmlpage->addToBodyContent($form);
                    break;
                } else {
                        $this->htmlpage->addToBodyContent(new Title(text: $aboutinfo['name'],
                                                                    class: $aboutinfo['name_class']));
                        $this->htmlpage->addToBodyContent(new BodyText(text: $aboutinfo['description'],
                                                                       class: $aboutinfo['description_class']));
                        $this->htmlpage->addToBodyContent(new Image(
                                                        name: $aboutinfo['imgFileName'],
                                                        class: $aboutinfo['img_class']
                        ));
                }
                break;
            case 'contact':
            case 'login':
            case 'register':
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                $form = $formFactory->createForm(
                                                form_info: $form_info, 
                                                field_info: $form_fields, 
                                                hidden_field_info: ['page' => $this->page], 
                                                field_text: [],
                                                class: $form_info["display_class"]
                                                );
                                                
                $this->htmlpage->addToBodyContent($form);
                break;
            case 'search':
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                $form = $formFactory->createForm(
                                                form_info: $form_info, 
                                                field_info: $form_fields, 
                                                hidden_field_info: ['page' => $this->page], 
                                                field_text: [],
                                                class: $form_info["display_class"]
                                                );
                                                
                $this->htmlpage->addToBodyContent($form);

                $columnsdata = ModelSelector::getWebsiteInfoModel()->getTableColumns(["title","lastEdit","rating"]);
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleBySearch();
                $tableFactory = new Table($columnsdata, $rowsdata);
                $this->htmlpage->addToBodyContent(new AtomicElement($tableFactory->createTable("table
                                                                                                table-hover 
                                                                                                table-striped
                                                                                                table-bordered")));
                break;
            case 'editArticle':
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page, $_GET["id"]); //give article tag
                $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($_GET["id"]);

                $form = $formFactory->createForm(form_info: $form_info, 
                                                 field_info: $form_fields,
                                                 hidden_field_info: ["user" => $_GET["id"]], //give article tag
                                                 class: $form_info["display_class"],
                                                 field_text: $bodyinfo);

                $this->htmlpage->addToBodyContent($form);
                break;
            case 'article':
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($_GET["id"]);
                $classes = ModelSelector::getWebsiteInfoModel()->getClasses($this->page);
                // ToDo: add accordion functionality to body text and code element
                $this->htmlpage->addToBodyContent(new Title(
                                                text: $bodyinfo['title'],
                                                class: $classes['title_class']
                                                ));
                $this->htmlpage->addToBodyContent(new AuthorText(
                                                text: "Author:" .$bodyinfo['name'] . "",
                                                class: $classes['author_class']
                                                ));
                $this->htmlpage->addToBodyContent(new BodyText(
                                                text: "<p1>" . $bodyinfo['summary'] . "</p>",
                                                class: $classes['body_class']
                ));
                $this->htmlpage->addToBodyContent(new CodeBlock(
                                                text: $bodyinfo['codeBlock'],
                                                class: $classes['codeblock_class']
                ));
                $this->htmlpage->addToBodyContent(new Image(
                                                name: $bodyinfo['imgFileName'],
                                                class: $classes['img_class']
                ));



                break;
            case 'dashboard':
                $this->htmlpage->addToBodyContent(new Title("Articles:"));
                $columnsdata = ModelSelector::getWebsiteInfoModel()->getTableColumns(["id","title","lastEdit"]);
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId(1);
                $tableFactory = new Table($columnsdata, $rowsdata);
                $this->htmlpage->addToBodyContent(new AtomicElement($tableFactory->createTable("table
                                                                                                table-hover 
                                                                                                table-striped
                                                                                                table-bordered")));
                break;
            default:
                throw new PageNotFoundException("No page defined for: '. '$this->page.'");
        }
    }

    private function addFooter()
    {
        $this->htmlpage->addToBodyContent(new Footer(
                                                    text: 'Christian, Danny, & Marius &copy' . date("Y") . '',
                                                    class:'border-top text-end bg-primary-subtle mt-auto'));
    }
}
