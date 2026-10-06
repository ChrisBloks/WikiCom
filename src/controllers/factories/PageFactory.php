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

use ArrayAccess,
Wiki\tools\traits\tErrorMessageCollector,
Wiki\models\ModelSelector,
Wiki\controllers\factories\MenuFactory,
Wiki\views\BasePage,
Wiki\views\containers\AtomicElement,
Wiki\views\containers\Header,
Wiki\views\containers\Toast,
Wiki\views\containers\Footer,
Wiki\views\containers\MainElement,
Wiki\dataObjects\ElementInfo;
use Wiki\controllers\ElementHandler;

class PageFactory
{
    use tErrorMessageCollector;
    private string $page;
    protected bool $isLoggedIn;
    protected array $response;
    private BasePage $htmlpage;
    protected array|ArrayAccess $article_info;

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
        $this->htmlpage->addtoHeadContent(new AtomicElement(new ElementInfo(["html_tag" => "title", "text" => "Testpage"])));
    }

    private function addScripts()
    {

        // should move to a config or something instead of pasting links raw in the pagefactory
        $this->htmlpage
            ->addToHeadContent(
                new AtomicElement(
                    new ElementInfo([
                        "text" => '
                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
                            <link rel="stylesheet" href="./src/css/stylesheet.css">
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/styles/default.min.css">'
                    ])
                )
            );

        $this->htmlpage
            ->addToHeadContent(
                new AtomicElement(
                    new ElementInfo([
                        "text" => '   <script type="module" src="./src/js/main.js"></script>
                            <script src="https://code.jquery.com/jquery-4.0.0.js"></script>
                            <script type="module" src="./src/js/main.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/highlight.min.js"></script>
                                        <script>hljs.highlightAll();</script>'
                    ])
                )
            );

        // switch ($this->page) {
        //     case 'editArticle':
        //     case 'search':
        //         $this->htmlpage->addToHeadContent(
        //             new AtomicElement(
        //                 new ElementInfo(
        //                     ["text" => '<script src="./src/js/searchPage.js"></script>']
        //                 )
        //             )
        //         );
        //         break;
        //     default:
        //         break;
        // }
    }


    public function addBody()
    {
        $styling_system = ModelSelector::getWebsiteInfoModel()->fetchSystemStyling();

        // title
        $this->htmlpage->addToBodyContent(new Header(
            ucfirst($this->page),
            $styling_system['header']
        ));



        // menu items
        // menu items from database
        $menu_items = ModelSelector::getWebsiteInfoModel()->fetchMenuItems($this->isLoggedIn);
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu(
            menu_items: $menu_items,
            class: $styling_system['menu_items']
        );
        $this->htmlpage->addToBodyContent($menu);

        $main = new MainElement();
        $main->addElement(new Toast(new ElementInfo()));
        $main->addElement(new AtomicElement(new ElementInfo(["text" => "<br>"])));

        // Maybe seperate controller
        $elements_info = ModelSelector::getElementModel()->fetchPageElements($this->page);


        // Add necessary variables
        $element_handler = new ElementHandler($this->response);
        $element_handler->addDataToElementList($elements_info);

        $element_list = [];
        $element_list[0] = $main;
        foreach ($elements_info as $element_info) {
            $element = ElementFactory::createElement($element_info);
            $element_list[$element_info['order_by']] = $element;
            $element_list[$element_info['parent_order']]->addElement($element);
        }

        // add the <main> to the body content
        $this->htmlpage->addToBodyContent($main);


        //add the footer to the body content
        $this->htmlpage->addToBodyContent(new AtomicElement(new ElementInfo(["text" => "<br>"])));
        $this->htmlpage->addToBodyContent(new Footer(
            text: 'Christian, Danny, & Marius &copy' . date("Y") . '',
            class: $styling_system['footer']
        ));
    }

    public function addCheckedUsingArray($form_fields, $response)
    {
        foreach ($form_fields as &$field) {
            if (!empty($response[$field['name']]) && is_array($response[$field['name']])) {
                foreach ($field['options'] as $key => $option) {
                    if (in_array($option['value'], $response[$field['name']])) {
                        $field['options'][$key]['checked'] = 1;
                    }
                }
            }
        }
        unset($field);

        return $form_fields;
    }
}
