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
    Wiki\views\containers\Card,
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
    Wiki\views\fields\ButtonField,
    InvalidArgumentException,
    Throwable;
use Wiki\dataObjects\ElementInfo;



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
                        "text" => '
                            <script src="https://code.jquery.com/jquery-4.0.0.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                            <script src="./vendor/webcito/bs-markdown-editor/dist/bs-markdown-editor.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/highlight.min.js"></script>
                            <script src="./src/js/wiki.js"></script>
                            <script>hljs.highlightAll();</script>'
                    ])
                )
            );

        switch ($this->page) {
            case 'editArticle':
            case 'search':
                $this->htmlpage->addToHeadContent(
                    new AtomicElement(
                        new ElementInfo(
                            ["text" => '<script src="./src/js/searchPage.js"></script>']
                        )
                    )
                );
                break;
            default:
                break;
        }
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
        $main->addElement(new AtomicElement(new ElementInfo(["text" => "<br>"])));

        // Maybe seperate controller
        $elements_info = ModelSelector::getElementModel()->fetchPageElements($this->page);

        foreach ($elements_info as &$element_info) {
            switch (true) {
                case $element_info['element_name'] === "random_article":
                    $excludelist = $excludelist ?? [];
                    $element_info['article'] = ModelSelector::getArticleModel()->fetchFrontPageArticles($excludelist);
                    $excludelist[] = $element_info['article']['id'];
                    break;
                case str_contains($element_info['element_name'], 'about_'):
                    $about_info = ModelSelector::getWebsiteInfoModel()->fetchAuthorAboutInfo($this->response['aboutID']);
                    $element_info['title'] = $about_info['name'];
                    $element_info['bodytext'] = $about_info['description'];
                    $element_info['image'] = $about_info['imgFileName'];
                    break;

                default:
                    break;
            }
        }
        unset($element_info);

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

// page building
        // switch ($this->page) {
        //     case 'home':

        //         // loop through list of element info
        //         foreach ($elements_info as $element_info) {
        //             // if parent_order is 0 this is not a sub container create element using a class from php_class and add the element to the list
        //             if ($element_info['parent_order'] == 0) {
        //                 $element = new $element_info['php_class']($element_info);
        //                 $element_info_list[$element_info['order_by']] = $element;
        //                 $main->addElement($element);
        //                 // if parent_order is not 0 this will be a subcontainer so created element needs to be added to a container based on parent_order
        //             } else {
        //                 $element_info_list[$element_info['order_by']] = new $element_info['php_class']($element_info);
        //                 $element_info_list[$element_info['parent_order']]->addElement($element_info_list[$element_info['order_by']]);
        //             }
        //         }

        //         break;

        //     case 'about':
        //         foreach ($elements_info as $element_info) {
        //             // if parent_order is 0 this is not a sub container create element using a class from php_class and add the element to the list
        //             if ($element_info['parent_order'] == 0) {
        //                 $element = new $element_info['php_class']($element_info);
        //                 $element_info_list[$element_info['order_by']] = $element;
        //                 $main->addElement($element);
        //                 // if parent_order is not 0 this will be a subcontainer so created element needs to be added to a container based on parent_order
        //             } else {
        //                 $element_info_list[$element_info['order_by']] = new $element_info['php_class']($element_info);
        //                 $element_info_list[$element_info['parent_order']]->addElement($element_info_list[$element_info['order_by']]);
        //             }
        //         }
        //         break;
        //     case 'contact':

        //         $element_list = [];
        //         $element_list[0] = $main;
        //         foreach ($elements_info as $element_info) {
        //             $element = ElementFactory::createElement($element_info);
        //             $element_list[$element_info['order_by']] = $element;
        //             $element_list[$element_info['parent_order']]->addElement($element);
        //         }
        //         break;
            // case 'login':
            // case 'register':
            //     $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
            //     $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);

            //     HtmlUtils::dump("field",$form_fields);
            //     HtmlUtils::dump("form",$form_info);
            //     // main Div: image + text-div 
            //     $main_container = new ContainerElement($styling_container['main_div'], '</div>');

            //     // sub text div: Title/Author/text/code
            //     $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');
            //     $formFactory = new FormFactory();



            //     $form = $formFactory->createForm(
            //         form_info: $form_info,
            //         field_info: $form_fields,
            //         hidden_field_info: ['page' => $this->page],
            //         field_default_text: []
            //     );
            //     $sub_container->addElement($form);
            //     $main_container->addElement($sub_container);
            //     $main->addElement($main_container);
            //     break;
            // case 'search':
            //     $container = new ContainerElement($styling_container['container_div'], '</div>');
            //     $row = new ContainerElement($styling_container['row_div'], '</div>');

            //     $filter_container = new ContainerElement($styling_container['filter_div'], '</div>');
            //     $results_container = new ContainerElement($styling_container['result_div'], '</div>');
            //     // ==================================================================================================
            //     // Search functionality
            //     $formFactory = new FormFactory();
            //     $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
            //     $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);


            //     $form_fields = $this->addCheckedUsingArray($form_fields, $this->response);

            //     $form = $formFactory->createForm(
            //         form_info: $form_info,
            //         field_info: $form_fields,
            //         hidden_field_info: ['page' => $this->page],
            //         field_default_text: ['sortby' => $this->response['sortby']],
            //     );

            //     $filter_container->addElement($form);
            //     // =================================================================================================
            //     // Table display

            //     // create checkbox inputs for filtering
            //     $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["title", "Author", "tags", "lastEdit", "rating"]);
            //     $rowsdata = ModelSelector::getArticleModel()->fetchArticleBySearch(
            //         author_ids: $this->response["Author"],
            //         tag_ids: $this->response["Tag"],
            //         sortBy: $this->response['sortby']
            //     );

            //     // print table for search results
            //     $tableFactory = new Table($columnsdata, $rowsdata);
            //     $results_container->addElement(new ContainerElement($styling_container['table_div'], '</div>'));
            //     $results_container->addElement(new AtomicElement($tableFactory->createTable($styling_container['table_class'])));


            //     //================================================================================================
            //     // Add containers to page
            //     $row->addElement($filter_container);
            //     $row->addElement($results_container);
            //     $container->addElement($row);

            //     $main->addElement($container);
            //     break;


            // case 'editArticle':
            //     // main Div: image + text-div 
            //     $main_container = new ContainerElement($styling_container['main_div'], '</div>');

            //     // sub text div: Title/Author/text/code
            //     $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');

            //     $formFactory = new FormFactory();
            //     $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page, $this->response['editArticleID']); //give article tag
            //     $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
            //     if ($this->response['editArticleID'] == 0) {
            //         $bodyinfo = isset($this->response['bodyinfo']) ? $this->response['bodyinfo'] : [];
            //     } else {
            //         $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($this->response['editArticleID']);
            //     }

            //     HtmlUtils::dump("test", $this->response);
            //     $form_fields = $this->addCheckedUsingArray($form_fields, $this->response);


            //     function array_find_index(array $haystack, callable $fn)
            //     {
            //         foreach ($haystack as $idx => $element) {
            //             if ($fn($element))
            //                 return $idx;
            //         }
            //         throw new InvalidArgumentException("Array does not contain a truthy element");
            //     }
            //     try {
            //         $form_fields[array_find_index($form_fields, fn($x) => $x['type'] == 'SearchableCheckboxes')]['addable_options'] = true;
            //     } catch (Throwable $e) {
            //         HtmlUtils::dump('error:', $e->getMessage());
            //     }

            //     $form = $formFactory->createForm(
            //         form_info: $form_info,
            //         field_info: $form_fields,
            //         hidden_field_info: ["articleID" => $this->response['editArticleID'], 'page' => $this->page, 'action' => 'saveArticle'],
            //         field_default_text: $bodyinfo,
            //     );

            //     // add to page
            //     $sub_container->addElement($form);
            //     $main_container->addElement($sub_container);
            //     $main->addElement($main_container);
            //     break;

            // case 'article':
            //     $this->htmlpage->addToHeadContent(new AtomicElement($styling_elements['article_script']));
            //     $converter = new GithubFlavoredMarkdownConverter([
            //         'html_input' => 'escape',
            //         'allow_unsafe_links' => false,
            //     ]);

            //     $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($this->response['articleID']);
            //     $tags = ModelSelector::getArticleModel()->fetchArticleTags($this->response['articleID']);
            //     $ratable = ($bodyinfo['user_id'] == $_SESSION['userID']) ? false : $this->response['isLoggedIn'];

            //     $main_container = new ContainerElement($styling_container['main_div'], '</div>');

            //     // Inner text div: Title/Author/text/code
            //     $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');

            //     // Top div witt[$h title, author, tags and decription title
            //     $main_container->addElement(new Title(
            //         text: ucfirst($bodyinfo['title']),
            //         class: $styling_elements['title_class']
            //     ));
            //     $main_container->addElement(new AuthorText(
            //         text: "Author: " . ucfirst($bodyinfo['name']) . "",
            //         class: $styling_elements['author_class']
            //     ));
            //     $main_container->addElement(new Rating(
            //         rating: $bodyinfo['rating'],
            //         article_id: $this->response['articleID'],
            //         ratable: $ratable,
            //         count: $bodyinfo['n_ratings']
            //     ));

            //     $tag_container = new ContainerElement($styling_container['tag_div'], '</div>');
            //     foreach ($tags as $key => $value) {
            //         $tag_id = ModelSelector::getArticleModel()->checkTagExists($value);

            //         $tag_container->addElement(new ButtonField(
            //             type: 'button',
            //             name: $tag_id['id'],
            //             class: $styling_elements["button_class"],
            //             label: $value,
            //             href: 'main.php?page=search&tag=' . urlencode($tag_id['id'])
            //         ));
            //     }
            //     $main_container->addElement($tag_container);
            //     $main_container->addElement(new Title(
            //         text: 'Description',
            //         class: $styling_elements['description_class']
            //     ));

            //     // Div with body text and image
            //     // purifier ini
            //     $config = HTMLPurifier_Config::createDefault();
            //     $config->set('HTML.Allowed', 'p,div[class],span[class],h1,h2,h3,h4,h5,h6,ul,ol,li,strong,em,a[href],img[src|alt|width|height],blockquote,code,pre,table,thead,tbody,tr,th,td,hr,br');

            //     $purifier = new HTMLPurifier($config);

            //     $bodytext = $converter->convert($bodyinfo['summary'])->getContent();
            //     $bodytext = $purifier->purify($bodytext);

            //     $sub_container->addElement(new BodyText(
            //         text: $bodytext,
            //         class: $styling_elements['body_class']
            //     ));
            //     $sub_container->addElement(new Image(
            //         name: './img/article/' . $bodyinfo['imgFileName'],
            //         class: $styling_elements['img_class']
            //     ));
            //     $main_container->addElement($sub_container);


            //     // bottom div with codeblock
            //     $bottom_container = new ContainerElement($styling_container['bot_div'], '</div>');
            //     $bottom_container->addElement(new Title(
            //         text: 'Code',
            //         class: "h4"
            //     ));
            //     $bottom_container->addElement(new CodeBlock(
            //         text: $bodyinfo['codeBlock'],
            //         class: $styling_elements['codeblock_class']
            //     ));

            //     // add to page
            //     $main->addElement($main_container);
            //     $main->addElement(new ContainerElement($styling_container['horizontal_rule'], ''));
            //     $main->addElement($bottom_container);
            //     break;

            // case 'dashboard':
            //     //====================================================================================================
            //     // add containers
            //     $container = new ContainerElement($styling_container["container_div"], '</div>');
            //     $row = new ContainerElement($styling_container["row_div"], '</div>');
            //     $left_container = new ContainerElement($styling_container["filter_div"], '</div>');
            //     $results_container = new ContainerElement($styling_container["result_div"], '</div>');
            //     $user_container = new ContainerElement($styling_container["user_div"], '</div>');

            //     $aboutinfo = ModelSelector::getUserInfoModel()->fetchUserInfoById($_SESSION['userID']);

            //     //====================================================================================================
            //     // table information
            //     $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["id", "title", "lastEdit"]);
            //     // add userID to fetcharticlebyUserId
            //     $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId($_SESSION['userID']);

            //     $formFactory = new FormFactory();
            //     $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
            //     $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);

            //     $user_container->addElement(new Image(
            //         name: './img/authors/' . $aboutinfo['imgFileName'],
            //         class: $styling_elements["img_class"]
            //     ));

            //     $user_container->addElement(new Title(
            //         text: $aboutinfo['name'],
            //         class: $styling_elements["user_title"]
            //     ));
            //     $left_container->addElement($user_container);
            //     $form = $formFactory->createForm(
            //         form_info: $form_info,
            //         field_info: [],
            //         hidden_field_info: ['page' => 'editArticle', 'id' => '0'],
            //         field_default_text: []
            //     );
            //     $left_container->addElement(new Title(
            //         text: $aboutinfo['email'],
            //         class: $styling_elements["email_class"]
            //     ));
            //     $left_container->addElement(new Title(
            //         text: "Create new article",
            //         class: $styling_elements["new_article_title"]
            //     ));
            //     $left_container->addElement($form);
            //     //===================================
            //     // goto edit user info

            //     $left_container->addElement(new Title(
            //         text: "Edit User information",
            //         class: "fs-3 border-top mt-3"
            //     ));
            //     $left_container->addElement(new ButtonField(
            //         type: "button",
            //         name: 'Edit User Information',
            //         class: 'btn btn-secondary mt-1',
            //         label: 'Change user information',
            //         id: $_SESSION['userID'],
            //         href: 'main.php?page=editUser&id=' . $_SESSION['userID']
            //     ));

            //     $left_container->addElement(new ButtonField(
            //         type: "button",
            //         name: 'Edit Password',
            //         class: 'btn btn-danger mt-1',
            //         label: 'Change Password',
            //         id: $_SESSION['userID'],
            //         href: 'main.php?page=editPassword&id=' . $_SESSION['userID']
            //     ));

            //     $tableFactory = new Table($columnsdata, $rowsdata);
            //     $results_container->addElement(new Title(
            //         text: "Articles",
            //         class: $styling_elements["articles_class"]
            //     ));
            //     $results_container->addElement(new AtomicElement($tableFactory->createTable($styling_container["table_class"])));

            //     $row->addElement($left_container);
            //     $row->addElement($results_container);
            //     $container->addElement($row);

            //     $main->addElement($container);
            //     break;
            // case 'editUser':
            //     // main Div: image + text-div 
            //     $main_container = new ContainerElement($styling_container['main_div'], '</div>');

            //     // sub text div: Title/Author/text/code
            //     $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');
            //     $formFactory = new FormFactory();
            //     $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
            //     $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);

            //     //HtmlUtils::dump('form info', $form_info);
            //     //HtmlUtils::dump('form fields', $form_fields);

            //     $form = $formFactory->createForm(
            //         form_info: $form_info,
            //         field_info: $form_fields,
            //         hidden_field_info: ['page' => $this->page],
            //     );
            //     $sub_container->addElement($form);
            //     $main_container->addElement($sub_container);
            //     $main->addElement($main_container);
            //     break;

            // case 'editPassword':
            //     // main Div: image + text-div 
            //     $main_container = new ContainerElement($styling_container['main_div'], '</div>');

            //     // sub text div: Title/Author/text/code
            //     $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');
            //     $formFactory = new FormFactory();
            //     $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
            //     $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);

            //     $form = $formFactory->createForm(
            //         form_info: $form_info,
            //         field_info: $form_fields,
            //         hidden_field_info: ['page' => $this->page],
            //     );

            //     $sub_container->addElement($form);
            //     $main_container->addElement($sub_container);
            //     $main->addElement($main_container);
            //     break;
        //     default:
        //         throw new PageNotFoundException("No page defined for: '. '$this->page.'");
        // }

        // end of switch statement