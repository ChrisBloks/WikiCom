<?php
// common parameters:
// Todo: save all commands as string and loop
// is instance of: checken voor interface class
require_once("./src/models/ModelSelector.php");
/* values to obtain from outside:
 *$isloggedIn;
 *$page_value;
 *$article_id;
 *$user_ids;
 *$tag_ids;
 *$sortBy;
 */
class PageFactory_v1
{
    private string $page;
    protected bool $isLoggedIn;
    public function __construct(string $page, bool $isLoggedIn = false)
    {
        $this->page = $page;
        $this->isLoggedIn = $isLoggedIn;
    }


    public function getElementsByPage(): BasePage
    {

        // start and header page
        $htmlpage = new BasePage();
        $htmlpage->addtoHeadContent(new AtomicElement("<title> Testpage </title>"));


        // title
        $htmlpage->addToBodyContent(new Header("<h1> Website </h1>"));

        // menu items

        // temporary hardcoded information for menu

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
        
        // tErrorMessage  ... eventually
        // tNoticeMessage ... eventually

        $formFactory = new FormFactory();
        $pageFields = new PageFields();

        switch ($this->page) {
            case 'home':
                $htmlpage->addToBodyContent(new AtomicElement("<p> Test for body element </p>"));
                break;
            case 'about':
                //  $htmlpage->addToBodyContent() <- here should be content dependant on the author you chose from the menu dropdown      
                $htmlpage->addToBodyContent(new AtomicElement("<p> Test about </p>"));
                break;
            case 'contact':
                $data = $pageFields->getFieldsForPage($this->page);
                $htmlpage->addToBodyContent($formFactory->createForm($data['form_info'], $data['form_fields'], []));
                break;
            case 'login':
                $data = $pageFields->getFieldsForPage($this->page);
                $htmlpage->addToBodyContent($formFactory->createForm($data['form_info'], $data['form_fields'], []));
                break;
            case 'register':
                $data = $pageFields->getFieldsForPage($this->page);
                $htmlpage->addToBodyContent($formFactory->createForm($data['form_info'], $data['form_fields'], []));
                break;
            //case 'article':
            //    // ToDo:
            //    break;
            case 'search':
               // Todo:
                $data = $pageFields->getFieldsForPage($this->page);
                $htmlpage->addToBodyContent($formFactory->createForm($data['form_info'], $data['form_fields'], []));          
                break;
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
