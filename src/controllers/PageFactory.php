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


    public function getElementsByPage(): iElement
    {

        // start and header page
        $htmlpage = new BasePage();
        $htmlpage->addtoHeadContent(new AtomicElement("<title> Testpage </title>"));

        // menu items
        // $menu_items = websiteModel->getMenuItems()       
        //  $htmlpage->addToHeadContent($menu_items)
        // tErrorMessage  ... eventually
        // tNoticeMessage ... eventually
        $htmlpage->addToBodyContent(new Header("Website"));
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
