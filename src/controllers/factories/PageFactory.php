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
Wiki\views\containers\NoticeMessage;



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
        $this->htmlpage->addToHeadContent(new AtomicElement('
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
                    rel="stylesheet">
                    <link href="./src//css/stylesheet.css" rel="stylesheet">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/styles/default.min.css">
                    '));

        $this->htmlpage->addToHeadContent(new AtomicElement(
            '
                    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/highlight.min.js"></script>
                    <script src="https://code.jquery.com/jquery-4.0.0.js"></script>
                    <script src="./src/js/wiki.js"></script>
                    <script>hljs.highlightAll();</script>'
        ));
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
        $styling_container = ModelSelector::getWebsiteInfoModel()->fetchContainerStylingByPage($this->page);
        $styling_elements = ModelSelector::getWebsiteInfoModel()->fetchElementStylingByPage($this->page);

        // page building
        switch ($this->page) {
            case 'home':

                $pageinfo = ModelSelector::getWebsiteInfoModel()->fetchBodyText($this->page);
                // get div styling from DB
                $container = new ContainerElement($styling_container['main_div'], '</div>');
                $container->addElement(new BodyText(
                    text: $pageinfo["bodytext"],
                    class: $styling_elements["bodytext_class"]
                ));
                $main->addElement($container);
                break;


            case 'about':
                $aboutinfo = ModelSelector::getWebsiteInfoModel()->fetchAuthorAboutInfo($this->response['aboutID']);

                if ($this->response['userID'] == $this->response['aboutID']) {
                    // Edit view: 
                    $top_container = new ContainerElement($styling_container['top_div'], '</div>');
                    $main_container = new ContainerElement($styling_container['main_div'], '</div>');
                    $sub_container = new ContainerElement('<div>', '</div>');

                    $formFactory = new FormFactory();
                    $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                    $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
                    $form = $formFactory->createForm(
                        form_info: $form_info,
                        field_info: $form_fields,
                        hidden_field_info: [
                            'user' => $this->response['aboutID'],
                            'page' => $this->page
                        ],
                        field_text: ["description" => $aboutinfo["description"]],
                        class: $form_info["display_class"],
                        submit_class: $form_info['submit_class']
                    );

                    $top_container->addElement(new Title(
                        text: $aboutinfo['name'],
                        class: $styling_elements['name_class']
                    ));

                    $sub_container->addElement(new Image(
                        name: './img/authors/' . $aboutinfo['imgFileName'],
                        class: $styling_elements['img_class']
                    ));

                    $main->addElement($top_container);
                    $main_container->addElement($sub_container);
                    $main_container->addElement($form);
                    $main->addElement($main_container);
                } else {
                    // Read-only view
                    $main_container = new ContainerElement($styling_container['main_div_2'], '</div>');
                    $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');

                    $sub_container->addElement(new Title(
                        text: $aboutinfo['name'],
                        class: $styling_elements['name_class']
                    ));
                    $sub_container->addElement(new BodyText(
                        text: $aboutinfo['description'],
                        class: $styling_elements['description_class']
                    ));

                    $main_container->addElement($sub_container);
                    $main_container->addElement(new Image(
                        name: './img/authors/' . $aboutinfo['imgFileName'],
                        class: $styling_elements['img_class']
                    ));

                    $main->addElement($main_container);
                }
                break;
            case 'contact':
            case 'login':
            case 'register':
                // main Div: image + text-div 
                $main_container = new ContainerElement($styling_container['main_div'], '</div>');

                // sub text div: Title/Author/text/code
                $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: $form_fields,
                    hidden_field_info: ['page' => $this->page],
                    field_text: [],
                    class: $form_info["display_class"],
                    submit_class: $form_info["submit_class"]
                );
                $sub_container->addElement($form);
                $main_container->addElement($sub_container);
                $main->addElement($main_container);
                break;
            case 'search':
                $container = new ContainerElement($styling_container['container_div'], '</div>');
                $row = new ContainerElement($styling_container['row_div'], '</div>');

                $filter_container = new ContainerElement($styling_container['filter_div'], '</div>');
                $results_container = new ContainerElement($styling_container['result_div'], '</div>');
                // ==================================================================================================
                // Search functionality
                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);

                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: $form_fields,
                    hidden_field_info: ['page' => $this->page],
                    field_text: ['sortby' => $this->response['sortby']],
                    class: $form_info["display_class"],
                    submit_class: $form_info["submit_class"],
                    field_array_values: $this->response['field_values']
                );

                $filter_container->addElement($form);
                // =================================================================================================
                // Table display

                // create checkbox inputs for filtering
                $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["title","tags","lastEdit", "rating"]);
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleBySearch(
                    author_ids: $this->response["Author"] ,
                    tag_ids: $this->response["Tag"] ,
                    sortBy: $this->response['sortby']
                );

                // print table for search results
                $tableFactory = new Table($columnsdata, $rowsdata);
                $results_container->addElement(new ContainerElement($styling_container['table_div'], '</div>'));
                $results_container->addElement(new AtomicElement($tableFactory->createTable($styling_container['table_class'])));


                //================================================================================================
                // Add containers to page
                $row->addElement($filter_container);
                $row->addElement($results_container);
                $container->addElement($row);

                $main->addElement($container);
                break;


            case 'editArticle':
                // main Div: image + text-div 
                $main_container = new ContainerElement(
                    $styling_container['main_div'],
                    '</div>'
                );

                // sub text div: Title/Author/text/code
                $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');

                // add tag functionality //TODO
                $add_tag_widget = new AtomicElement('<div id="add-tag-widget" class="d-flex gap-2 mt-2 mb-2">
                                                    <input type="text" id="new-tag-name" 
                                                        class="form-control form-control-sm" placeholder="New tag">
                                                    <button type="button" id="add-tag-btn" 
                                                    class="btn btn-sm btn-secondary">Add tag</button>
                                                    </div>');

                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page, $this->response['editArticleID']); //give article tag
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
                if ($this->response['editArticleID'] == 0) {
                    $bodyinfo = isset($this->response['bodyinfo'])? $this->response['bodyinfo']:[];
                }
                else {
                    $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($this->response['editArticleID']);
                }

                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: $form_fields,
                    hidden_field_info: ["articleID" => $this->response['editArticleID'], 'page' => $this->page,'action' =>'saveArticle'], 
                    class: $form_info["display_class"],
                    field_text: $bodyinfo,
                    submit_class: $form_info['submit_class'],
                    field_array_values: isset($this->response['field_values']) ? $this->response['field_values']:[]
                );

                // add to page
                $sub_container->addElement($form);
                $main_container->addElement($add_tag_widget);
                $main_container->addElement($sub_container);
                $main->addElement($main_container);
                break;

            case 'article':
                $this->htmlpage->addToHeadContent(new AtomicElement($styling_elements['article_script']));

                $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($this->response['articleID']);
                $tags = ModelSelector::getArticleModel()->fetchArticleTags($this->response['articleID']);
                $ratable = ($bodyinfo['user_id'] == $_SESSION['userID']) ? false : $this->response['isLoggedIn'];

                $main_container = new ContainerElement($styling_container['main_div'], '</div>');

                // Inner text div: Title/Author/text/code
                $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');

                // Top div with title, author, tags and decription title
                $main_container->addElement(new Title(
                    text: ucfirst($bodyinfo['title']),
                    class: $styling_elements['title_class']
                ));
                $main_container->addElement(new AuthorText(
                    text: "Author: " . ucfirst($bodyinfo['name']) . "",
                    class: $styling_elements['author_class']
                ));
                $main_container->addElement(new Rating(
                    rating: $bodyinfo['rating'],
                    article_id: $this->response['articleID'],
                    ratable: $ratable
                ));
                $display_tags = '';
                foreach ($tags as $key => $value) {
                    $tag_id = ModelSelector::getArticleModel()->checkTagExists($value);
                    $display_tags .= '<a href="main.php?page=search&tag='.$tag_id['id'].'">' . $value . ' </a>';
                }
                $main_container->addElement(new BodyText(
                    text: $display_tags,
                    class: 'border-bottom border-top mb-3'
                ));

                $main_container->addElement(new Title(
                    text: 'Description',
                    class: "h4 mb-4"
                ));

                // Div with body text and image
                $sub_container->addElement(new BodyText(
                    text: ucfirst($bodyinfo['summary']),
                    class: $styling_elements['body_class']
                ));
                $sub_container->addElement(new Image(
                    name: './img/article/' . $bodyinfo['imgFileName'],
                    class: $styling_elements['img_class']
                ));
                $main_container->addElement($sub_container);


                // bottom div with codeblock
                $bottom_container = new ContainerElement($styling_container['bot_div'], '</div>');
                $bottom_container->addElement(new Title(
                    text: 'Code',
                    class: "h4"
                ));
                $bottom_container->addElement(new CodeBlock(
                    text: $bodyinfo['codeBlock'],
                    class: $styling_elements['codeblock_class']
                ));

                // add to page
                $main->addElement($main_container);
                $main->addElement(new ContainerElement($styling_container['horizontal_rule'],''));
                $main->addElement($bottom_container);
                break;

            case 'dashboard':
                //====================================================================================================
                // add containers
                $container = new ContainerElement($styling_container["container_div"], '</div>');
                $row = new ContainerElement($styling_container["row_div"], '</div>');
                $left_container = new ContainerElement($styling_container["filter_div"], '</div>');
                $results_container = new ContainerElement($styling_container["result_div"], '</div>');
                $user_container = new ContainerElement($styling_container["user_div"], '</div>');

                $aboutinfo = ModelSelector::getWebsiteInfoModel()->fetchAuthorAboutInfo($_SESSION['userID']);

                //====================================================================================================
                // table information
                $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["id", "title", "lastEdit"]);
                // add userID to fetcharticlebyUserId
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId($_SESSION['userID']);

                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);

                $user_container->addElement(new Image(
                    name: './img/authors/' . $aboutinfo['imgFileName'],
                    class: $styling_elements["img_class"]
                ));

                $user_container->addElement(new Title(
                    text: $aboutinfo['name'],
                    class: $styling_elements["user_title"]
                ));
                $left_container->addElement($user_container);
                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: [],
                    hidden_field_info: ['page' => 'editArticle', 'id' => '0'],
                    class: $form_info["display_class"],
                    field_text: [],
                    submit_class: $form_info["submit_class"]
                );
                $left_container->addElement(new Title(
                    text: "Create new article",
                    class: $styling_elements["new_article_title"]
                ));
                $left_container->addElement($form);

                $tableFactory = new Table($columnsdata, $rowsdata);
                $results_container->addElement(new Title(
                    text: "Articles",
                    class: $styling_elements["articles_class"]
                ));
                $results_container->addElement(new AtomicElement($tableFactory->createTable($styling_container["table_class"])));

                $row->addElement($left_container);
                $row->addElement($results_container);
                $container->addElement($row);

                $main->addElement($container);
                break;
            default:
                throw new PageNotFoundException("No page defined for: '. '$this->page.'");
        }

        // end of switch statement
        // add the <main> to the body content
        $this->htmlpage->addToBodyContent($main);


        //add the footer to the body content
        $this->htmlpage->addToBodyContent(new Footer(
            text: 'Christian, Danny, & Marius &copy' . date("Y") . '',
            class: $styling_system['footer']
        ));

    }

}
