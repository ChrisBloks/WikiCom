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
    public function __construct(string $page, bool $isLoggedIn = true)
    {
        $this->page = $page;
        $this->isLoggedIn = $isLoggedIn;
        $this->htmlpage = new Basepage;
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
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
                        rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
                        crossorigin="anonymous">'));
        $this->htmlpage->addToHeadContent(new AtomicElement('
                        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
                        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" 
                        crossorigin="anonymous"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
                        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" 
                        crossorigin="anonymous">
                        </script>'));
    }


    public function addBodyContent()
    {
        // ToDo: make error element, pass array with errors
        if ($this->hasErrors() == true) {
            HtmlUtils::dump("Errors", $this->getErrors());
        }

        // tNoticeMessage ... eventually

        // title
        $this->htmlpage->addToBodyContent(new Header('<h1> Website </h1>'));

        // menu items
        // menu items from database
        $menu_items = ModelSelector::getWebsiteInfoModel()->getMenuItems($this->isLoggedIn);
        // verander createMenu($menu,items, isloggedin) naar true voor de andere  menustructuur
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu($menu_items, true);
        $this->htmlpage->addToBodyContent($menu);
        if ($menuFactory->hasErrors()) {
            foreach ($menuFactory->getErrors() as $error) {
                $this->htmlpage->addToBodyContent(new AtomicElement("- $error <br> ====================<br>"));
            }
        } else {
            $this->htmlpage->addToBodyContent(new AtomicElement('<p ' . HtmlUtils::addClassAttr("w3-xlarge") . '>(no menu errors)<br></p>'));
        }



        switch ($this->page) {
            case 'home':
                $bodytext = ModelSelector::getWebsiteInfoModel()->getBodyText($this->page)["bodytext"];
                $this->htmlpage->addToBodyContent(new BodyText($bodytext));
                break;
            case 'about':
                if (empty($_GET["author"])) {
                    $bodytext = ModelSelector::getWebsiteInfoModel()->getBodyText($this->page)["bodytext"];
                    $this->htmlpage->addToBodyContent(new BodyText($bodytext));
                    break;
                }

                $bodytext = ModelSelector::getWebsiteInfoModel()->getAuthorAboutInfo($_GET["author"])['description'];

                if (true) // author equals user
                    {
                        $formFactory = new FormFactory();
                        $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page);
                        $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                        $form = $formFactory->createForm($form_info, $form_fields, [],$bodytext);
                        $this->htmlpage->addToBodyContent($form);
                        break;                        
                    }
                
                $this->htmlpage->addToBodyContent(new BodyText($bodytext)); 
                break;
            case 'contact':
            case 'login':
            case 'register':
            case 'search':
            case 'editArticle':
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                HtmlUtils::dump("field ino",$form_fields, false);
                $this->htmlpage->addToBodyContent($formFactory->createForm($form_info, $form_fields, []));
                break;
            case 'article':
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById(2);
                foreach ($bodyinfo as $key => $value) {
                    $this->htmlpage->addToBodyContent(new BodyText($value));
                }
                break;
            case 'dashboard':
                $columnsdata = ModelSelector::getWebsiteInfoModel()->getTableColumns();
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId(1);
                $tableFactory = new TableFactoryV2($columnsdata, $rowsdata);
                $this->htmlpage->addToBodyContent(new AtomicElement($tableFactory->createTable()));
                break;
            default:
                throw new PageNotFoundException("No page defined for: '. '$this->page.'");
        }
    }

    private function addFooter()
    {
        $this->htmlpage->addToBodyContent(new Footer("Footer text"));
    }
}
