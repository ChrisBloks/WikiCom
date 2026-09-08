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
Wiki\views\containers\MainElement,
Wiki\views\containers\Rating,
Wiki\views\containers\NoticeMessage,
League\CommonMark\GithubFlavoredMarkdownConverter,
HTMLPurifier,
HTMLPurifier_Config,
Wiki\views\fields\ButtonField;



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
        // should move to a config or something instead of pasting links raw in the pagefactory
        $this->htmlpage->addToHeadContent(new AtomicElement('
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
                <link rel="stylesheet" href="./src/css/stylesheet.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/styles/default.min.css">
    '));

        $this->htmlpage->addToHeadContent(new AtomicElement('
                <script src="https://code.jquery.com/jquery-4.0.0.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                <script src="./vendor/webcito/bs-markdown-editor/dist/bs-markdown-editor.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/highlight.min.js"></script>
                <script src="./src/js/wiki.js"></script>
                <script>hljs.highlightAll();</script>'
        ));
    }


    public function addBody()
    {
        $styling_system = ModelSelector::getWebsiteInfoModel()->fetchSystemStyling();
        $styling_container = ModelSelector::getWebsiteInfoModel()->fetchContainerStylingByPage($this->page);
        $styling_elements = ModelSelector::getWebsiteInfoModel()->fetchElementStylingByPage($this->page);

        // title
        $this->htmlpage->addToBodyContent(new Header(
            ucfirst($this->page),
            $styling_system['header']
        ));

        // menu items
        // menu items from database
        // verander createMenu($menu,items, isloggedin) naar true voor de andere  menustructuur
        $menu_items = ModelSelector::getWebsiteInfoModel()->fetchMenuItems($this->isLoggedIn);
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu(
            menu_items: $menu_items,
            class: $styling_system['menu_items']
        );
        $this->htmlpage->addToBodyContent($menu);

        $main = new MainElement();
        $main->addElement(new NoticeMessage());

        // Build <main>
        $page_elements = ModelSelector::getWebsiteInfoModel()->fetchElementInfoByPage($this->page);
        $element_list = [0 => $main];

        $elementFactory = new ElementFactory();
        foreach ($page_elements as $element_id => $element_info){
            $element = $elementFactory->createNewElement($element_info);
            $element_list[$element_id] = $element;
            $element_list[$element_info['parent_id']]->addElement($element);
        }
        
        // add the <main> to the body content
        $this->htmlpage->addToBodyContent($main);


        //add the footer to the body content
        $this->htmlpage->addToBodyContent(new Footer(
            text: 'Christian, Danny, & Marius &copy' . date("Y") . '',
            class: $styling_system['footer']
        ));

    }

}
