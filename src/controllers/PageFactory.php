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
class PageFactory_v1
{
    private array $pagecontainer = [];
    // get page from the page controller;
    private string $page;
    public function __construct(string $page)
    {
        $this->page = $page;
    }


    public function getElementsByPage()
    {

        // start and header page
        $htmlpage = new BasePage();
        $htmlpage->addtoHeadContent(new AtomicElement("<title> Testpage </title>"));


        // title
        $htmlpage->addToBodyContent(new Header("<h1> Website </h1>"));

        // menu items

        // temporary hardcoded information for menu
        $menu_items = [
            ['label' => 'Home', 'href' => 'index.php?page=home'],
            [
                'label' => 'About',
                'href' => 'index.php?page=about',
                'submenu' => [
                    ['label' => 'Author One', 'href' => 'index.php?page=about&author=1'],
                    ['label' => 'Author Two', 'href' => 'index.php?page=about&author=2'],
                ]
            ],
            ['label' => 'Search', 'href' => 'index.php?page=search'],
            ['label' => 'Article', 'href' => 'index.php?page=article'],
            ['label' => 'Contact', 'href' => 'index.php?page=contact'],
            ['label' => 'Login', 'href' => 'index.php?page=login', 'guest_only' => true],
            ['label' => 'Register', 'href' => 'index.php?page=register', 'guest_only' => true],
            ['label' => 'Dashboard', 'href' => 'index.php?page=dashboard', 'auth_only' => true],
            ['label' => 'Logout', 'href' => 'index.php?page=logout', 'auth_only' => true],
            // test for tErrorMessageCollector malformed, to test the error collector:
            ['label' => 'Broken Item'],
        ];

        // verander createMenu($menu,items, isloggedin) naar true voor de andere  menustructuur
        $htmlpage->addToBodyContent(new AtomicElement("Menu (isLoggedIn = false) ===== <br><br>"));
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu($menu_items, false);

        $htmlpage->addToBodyContent(new AtomicElement('----- Collected errors -----<br><br>'));
        if ($menuFactory->hasErrors()) {
            foreach ($menuFactory->getErrors() as $error) {
                $htmlpage->addToBodyContent(new AtomicElement("- $error <br> ====================<br>"));
            }
        } else {
            $htmlpage->addToBodyContent(new AtomicElement("(none)<br>"));
        }
        $htmlpage->addToBodyContent($menu);
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
            //case 'search':
            //    // Todo:
            //    // get $form_info and $form_fields from db method here
            //     $htmlpage->addToBodyContent($formFactory->createForm($form_info, $form_fields, []));                
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
        $htmlpage->show();
    }
}
