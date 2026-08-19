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
    public function __construct(string $page, bool $isLoggedIn = false)
    {
        $this->page = $page;
        $this->isLoggedIn = $isLoggedIn;
        $this->htmlpage = new Basepage;
        // check user loginstatus
        if (isset($_SESSION['userName'])) {
            $this->isLoggedIn = true;
        } else {
            $this->isLoggedIn = false;
        }
    }

    public function show()
    {
        $this->addHead();
        $this->addScripts();

        $this->addBodyContent();
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


    public function addBodyContent()
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
                                    isLoggedIn: $this->isLoggedIn, 
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

        // page navigation
        switch ($this->page) {
            case 'home':
                $bodytext = ModelSelector::getWebsiteInfoModel()->getBodyText($this->page)["bodytext"];
                $this->htmlpage->addToBodyContent(new BodyText(
                                                text: $bodytext,
                                                class: 'fs-2 text-center'));
                break;
            case 'about':
                if (empty($_GET["author"])) {
                    $bodytext = ModelSelector::getWebsiteInfoModel()->getBodyText($this->page)["bodytext"];
                    $this->htmlpage->addToBodyContent(new BodyText($bodytext));
                    break;
                }

                $bodytext = ModelSelector::getWebsiteInfoModel()->getAuthorAboutInfo($_GET["author"]);

                if ($this->isLoggedIn === true) // author equals user
                {
                    $formFactory = new FormFactory();
                    $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page);
                    $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                    $form = $formFactory->createForm($form_info, $form_fields, [], ["abouttext" => $bodytext]);
                    // @JJ-Danny-Hu kun je de username hier ook uit fetchen, dan kan die als title erbij?
                    $form->addHiddenField("user", $_GET["author"]);
                    $this->htmlpage->addToBodyContent($form);
                    break;
                } else {
                    // @JJ-Danny-Hu kun je de username hier ook uit fetchen, dan kan die als title erbij?
                        $this->htmlpage->addToBodyContent(new Title($bodytext['name']));
                        $this->htmlpage->addToBodyContent(new BodyText($bodytext['description']));
                }
                break;
            case 'contact':
            case 'login':
            case 'register':
            case 'search':
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                $form = $formFactory->createForm(
                                                form_info: $form_info, 
                                                field_info: $form_fields, 
                                                hidden_field_info: [], 
                                                text: ["articletext" => "testtext", "articlecodeblock" => "testcode"]);
                // @JJ-Danny-Hu idealiter geven we voor elk element ook een class-beschrijving mee
                // dan kunnen we met een for loop ze ook aan de pagina toevoegen
                $this->htmlpage->addToBodyContent($form);
                break;
            case 'editArticle':
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page, $_GET["id"]); //give article tag
                $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($_GET["id"]);

                $form = $formFactory->createForm($form_info, $form_fields, [], $bodyinfo);
                $form->addHiddenField("user", $_GET["id"]); //give article tag
                $this->htmlpage->addToBodyContent($form);
                break;
            case 'article':
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($_GET["id"]);

                // ToDo: add accordion functionality to body text and code element
                $this->htmlpage->addToBodyContent(new Title($bodyinfo['title']));
                $this->htmlpage->addToBodyContent(new AtomicElement(
                                                html: "<h3> Author:" .$bodyinfo['name'] . "</h3>",
                                                ));
                $this->htmlpage->addToBodyContent(new BodyText(
                                                text: "<p1>" . $bodyinfo['summary'] . "</p>",
                                                class: 'accordion accordion-item'
                ));
                $this->htmlpage->addToBodyContent(new AtomicElement('<pre><code class="php">' 
                                                                        . $bodyinfo['codeBlock']
                                                                        . '</code></pre>'));


                break;
            case 'dashboard':
                $this->htmlpage->addToBodyContent(new Title("Articles:"));
                $columnsdata = ModelSelector::getWebsiteInfoModel()->getTableColumns();
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId(1);
                $tableFactory = new Table($columnsdata, $rowsdata);
                HtmlUtils::dump('table', $tableFactory);
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
        $this->htmlpage->addToBodyContent(new Footer('Christian, Danny, & Marius &copy' . date("Y") . '',
                                                    'border-top fixed-bottom text-end bg-primary-subtle'));
    }
}
