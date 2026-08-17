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
    public function __construct(string $page, bool $isLoggedIn = true)
    {
        $this->page = $page;
        $this->isLoggedIn = $isLoggedIn;
    }


    public function createPage(): BasePage
    {

        // start and header page
        $htmlpage = new BasePage();
        $htmlpage->addtoHeadContent(new AtomicElement("<title> Testpage </title>"));

        /*
        * Errors van instances worden niet opgeslagen in pagefactory errors.
                
        */

        // ToDo: make error element, pass array with errors
        if ($this->hasErrors()==true) {
            HtmlUtils::dump("Errors",$this->getErrors());
        }
                
        // tNoticeMessage ... eventually

        // title
        $htmlpage->addToBodyContent(new Header("<h1> Website </h1>"));

        // menu items

        // menu items from database

        $menu_items = ModelSelector::getWebsiteInfoModel()->getMenuItems($this->isLoggedIn);
       

        // verander createMenu($menu,items, isloggedin) naar true voor de andere  menustructuur
        $htmlpage->addToBodyContent(new AtomicElement("Menu (isLoggedIn = false) ===== <br><br>"));
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu($menu_items, true);
        $htmlpage->addToBodyContent($menu);
        $htmlpage->addToBodyContent(new AtomicElement('----- Collected errors -----<br><br>'));
        if ($menuFactory->hasErrors()) {
            foreach ($menuFactory->getErrors() as $error) {
                $htmlpage->addToBodyContent(new AtomicElement("- $error <br> ====================<br>"));
            }
        } else {
            $htmlpage->addToBodyContent(new AtomicElement("(none)<br>"));
        }
        


        switch ($this->page) {
            case 'home':
                $bodytext = ModelSelector::getWebsiteInfoModel()->getBodyText($this->page)["bodytext"];
                $htmlpage->addToBodyContent(new BodyText($bodytext));
                break;
            case 'about':
                if (empty($_GET["author"]))
                {
                    $bodytext = ModelSelector::getWebsiteInfoModel()->getBodyText($this->page)["bodytext"];
                    $htmlpage->addToBodyContent(new BodyText($bodytext));
                    break;
                }
                $bodytext = ModelSelector::getWebsiteInfoModel()->getAuthorAboutInfo($_GET["author"])['description'];
                $htmlpage->addToBodyContent(new BodyText($bodytext)); 
                break;
            case 'contact':
            case 'login':
            case 'register':
            case 'search':
            case 'editArticle':
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->getFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->getFormInfo($this->page);
                $htmlpage->addToBodyContent($formFactory->createForm($form_info, $form_fields, []));
                break;
            case 'article':
                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById(2);
                foreach ($bodyinfo as $key => $value) {
                    $htmlpage->addToBodyContent(new BodyText($value)); 
                }
                break;
            case 'dashboard':
               $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId(1);
               $columnsdata = ModelSelector::getWebsiteInfoModel()->getTableColumns();
               $tableFactory = new TableFactoryV2($columnsdata, $rowsdata);

               print_r($tableFactory->createTable());
            //    $table = $tableFactory->createTable($columns, $rows);
            //    $htmlpage ->addToBodyContent($table);
               break;
            //case 'editArticle':
            //    // get $form_info and $form_fields from db method here
            //     $htmlpage->addToBodyContent($formFactory->createForm($form_info, $form_fields, []));
            default:
                throw new PageNotFoundException("No page defined for: '. '$this->page.'");
        }
        $htmlpage->addToBodyContent(new Footer("Footer text"));
        return $htmlpage;
    }
}
