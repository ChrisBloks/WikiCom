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
    public function __construct(string $page, bool $isLoggedIn = false)
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

        $menu_items = ModelSelector::callModel('website')->getMenuItems($this -> isLoggedIn);
       

        // verander createMenu($menu,items, isloggedin) naar true voor de andere  menustructuur
        $htmlpage->addToBodyContent(new AtomicElement("Menu (isLoggedIn = false) ===== <br><br>"));
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu($menu_items, false);
        $htmlpage->addToBodyContent($menu);
        $htmlpage->addToBodyContent(new AtomicElement('----- Collected errors -----<br><br>'));
        if ($menuFactory->hasErrors()) {
            foreach ($menuFactory->getErrors() as $error) {
                $htmlpage->addToBodyContent(new AtomicElement("- $error <br> ====================<br>"));
            }
        } else {
            $htmlpage->addToBodyContent(new AtomicElement("(none)<br>"));
        }
        

        $formFactory = new FormFactory();
        $pageFields = new PageFields();

        switch ($this->page) {
            case 'home':
                $bodytext = ModelSelector::callModel('website')->getBodyText($this->page)["bodytext"];
                $htmlpage->addToBodyContent(new BodyText($bodytext));
                break;
            case 'about':
                $bodytext = ModelSelector::callModel('website')->getBodyText($this->page,1)["description"];
                $htmlpage->addToBodyContent(new BodyText($bodytext)); 
                break;
            case 'contact':
            case 'login':
            case 'register':
                $form_fields = ModelSelector::callModel('form')->getFieldInfo($this->page);
                $data = $pageFields->getFieldsForPage($this->page);
                $htmlpage->addToBodyContent($formFactory->createForm($data['form_info'], $form_fields, []));
                break;
            //case 'article':
            //    // ToDo:
            //    break;
            case 'search':
               // Todo:
                $form_fields = ModelSelector::callModel('form')->getFieldInfo($this->page);
                $data = $pageFields->getFieldsForPage($this->page);
                $htmlpage->addToBodyContent($formFactory->createForm($data['form_info'], $form_fields, []));          
                break;
            //case 'article':
            //    // ToDo:
            //    break;

            //case 'dashboard':
            //    // get $form_info and $form_fields from db method here
            //     $htmlpage->addToBodyContent($formFactory->createForm($form_info, $form_fields, []));
            //    break;
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
