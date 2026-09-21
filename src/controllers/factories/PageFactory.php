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
    Wiki\views\containers\Modal,
    Wiki\views\containers\CodeBlock,
    Wiki\views\containers\Footer,
    Wiki\views\containers\ContainerElement,
    Wiki\views\containers\MainElement,
    Wiki\views\containers\Rating,
    Wiki\views\containers\Toast,
    Wiki\views\containers\Card,
    Wiki\views\containers\NoticeMessage,
    League\CommonMark\GithubFlavoredMarkdownConverter,
    HTMLPurifier,
    HTMLPurifier_Config,
    Wiki\views\fields\ButtonField,
    InvalidArgumentException,
    Throwable;



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

        $this->htmlpage->addToHeadContent(new AtomicElement(
            '
                <script src="https://code.jquery.com/jquery-4.0.0.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
                <script src="./vendor/webcito/bs-markdown-editor/dist/bs-markdown-editor.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.12.0/highlight.min.js"></script>
                <script src="./src/js/wiki.js"></script>
                <script>hljs.highlightAll();</script>'
        ));

        switch ($this->page) {
            case 'editArticle':
            case 'search':
                $this->htmlpage->addToHeadContent(
                    new AtomicElement(
                        '<script src="./src/js/searchPage.js"></script>'
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
        $menu_items = ModelSelector::getWebsiteInfoModel()->fetchMenuItems($this->isLoggedIn);
        $menuFactory = new MenuFactory();
        $menu = $menuFactory->createMenu(
            menu_items: $menu_items,
            class: $styling_system['menu_items']
        );
        $this->htmlpage->addToBodyContent($menu);

        $main = new MainElement();
        $notice_container = new ContainerElement('<div id="page-notices">', '</div>');
        // $notice_container->addElement(new NoticeMessage());
        // $main->addElement($notice_container);
        $main->addElement(new Toast());
        $main->addElement(new AtomicElement("<br>"));



        // page building
        switch ($this->page) {
            case 'home':

                $articles = ModelSelector::getArticleModel()->fetchFrontPageArticles();
                $pageinfo = ModelSelector::getWebsiteInfoModel()->fetchBodyText($this->page);
                $container = new ContainerElement($styling_container['main_div'], '</div>');
                $container->addElement(new Title($pageinfo['bodytext'], 'display-1 border-bottom mb-1'));

                // outer row
                $row_container = new ContainerElement('<div class="row g-4 mb-5">', '</div>');

                // first article = featured, wrapped in col-md-8
                $featured = array_shift($articles);
                $featured_col = new ContainerElement('<div class="col-md-8">', '</div>');
                $featured_col->addElement(new Card(
                    image: $featured['imgFileName'],
                    title: $featured['title'],
                    summary: $featured['summary'],
                    article_id: $featured['id']
                ));
                $row_container->addElement($featured_col);

                // remaining articles = small cards, wrapped in col-md-4 > row > col-12 each
                $small_col = new ContainerElement('<div class="col-md-4">', '</div>');
                $small_row = new ContainerElement('<div class="row g-4">', '</div>');

                foreach ($articles as $a) {
                    $small_wrapper = new ContainerElement('<div class="col-12">', '</div>');
                    $small_wrapper->addElement(new Card(
                        image: $a['imgFileName'],
                        title: $a['title'],
                        summary: $a['summary'],
                        article_id: $a['id']
                    ));
                    $small_row->addElement($small_wrapper);
                }

                $small_col->addElement($small_row);
                $row_container->addElement($small_col);

                $container->addElement($row_container);
                $main->addElement($container);
                break;

            case 'about':
                $aboutinfo = ModelSelector::getWebsiteInfoModel()->fetchAuthorAboutInfo($this->response['aboutID']);

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
                    field_default_text: []
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


                $form_fields = $this->addCheckedUsingArray($form_fields, $this->response);

                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: $form_fields,
                    hidden_field_info: ['page' => $this->page],
                    field_default_text: ['sortby' => $this->response['sortby']],
                );

                $filter_container->addElement($form);
                // =================================================================================================
                // Table display

                // create checkbox inputs for filtering
                $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["title", "Author", "tags", "lastEdit", "rating"]);
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleBySearch(
                    author_ids: $this->response["Author"],
                    tag_ids: $this->response["Tag"],
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
                $main_container = new ContainerElement($styling_container['main_div'], '</div>');

                // sub text div: Title/Author/text/code
                $sub_container = new ContainerElement($styling_container['sub_div'], '</div>');

                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page, $this->response['editArticleID']); //give article tag
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);
                if ($this->response['editArticleID'] == 0) {
                    $bodyinfo = isset($this->response['bodyinfo']) ? $this->response['bodyinfo'] : [];
                } else {
                    $bodyinfo = ModelSelector::getArticleModel()->fetchArticleById($this->response['editArticleID']);
                }
                $form_fields = $this->addCheckedUsingArray($form_fields, $this->response);


                function array_find_index(array $haystack, callable $fn)
                {
                    foreach ($haystack as $idx => $element) {
                        if ($fn($element))
                            return $idx;
                    }
                    throw new InvalidArgumentException("Array does not contain a truthy element");
                }
                try {
                    $form_fields[array_find_index($form_fields, fn($x) => $x['type'] == 'SearchableCheckboxes')]['addable_options'] = true;
                } catch (Throwable $e) {
                    HtmlUtils::dump('error:', $e->getMessage());
                }

                $form = $formFactory->createForm(
                    form_info: $form_info,
                    field_info: $form_fields,
                    hidden_field_info: ["articleID" => $this->response['editArticleID'], 'page' => $this->page, 'action' => 'saveArticle'],
                    field_default_text: $bodyinfo,
                );

                // add to page
                $sub_container->addElement($form);
                $main_container->addElement($sub_container);
                $main->addElement($main_container);
                break;

            case 'article':
                $this->htmlpage->addToHeadContent(new AtomicElement($styling_elements['article_script']));
                $converter = new GithubFlavoredMarkdownConverter([
                    'html_input' => 'escape',
                    'allow_unsafe_links' => false,
                ]);

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
                    ratable: $ratable,
                    count: $bodyinfo['n_ratings']
                ));

                $tag_container = new ContainerElement($styling_container['tag_div'], '</div>');
                foreach ($tags as $key => $value) {
                    $tag_id = ModelSelector::getArticleModel()->checkTagExists($value);

                    $tag_container->addElement(new ButtonField(
                        type: 'button',
                        name: $tag_id['id'],
                        class: $styling_elements["button_class"],
                        label: $value,
                        href: 'main.php?page=search&tag=' . urlencode($tag_id['id'])
                    ));
                }
                $main_container->addElement($tag_container);
                $main_container->addElement(new Title(
                    text: 'Description',
                    class: $styling_elements['description_class']
                ));

                // Div with body text and image
                // purifier ini
                $config = HTMLPurifier_Config::createDefault();
                $config->set('HTML.Allowed', 'p,div[class],span[class],h1,h2,h3,h4,h5,h6,ul,ol,li,strong,em,a[href],img[src|alt|width|height],blockquote,code,pre,table,thead,tbody,tr,th,td,hr,br');

                $purifier = new HTMLPurifier($config);

                $bodytext = $converter->convert($bodyinfo['summary'])->getContent();
                $bodytext = $purifier->purify($bodytext);

                $sub_container->addElement(new BodyText(
                    text: $bodytext,
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
                $main->addElement(new ContainerElement($styling_container['horizontal_rule'], ''));
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

                $aboutinfo = ModelSelector::getUserInfoModel()->fetchUserInfoById($_SESSION['userID']);

                //====================================================================================================
                // table information
                $columnsdata = ModelSelector::getWebsiteInfoModel()->fetchTableColumns(["id", "title", "lastEdit"]);
                $rowsdata = ModelSelector::getArticleModel()->fetchArticleByUserId($_SESSION['userID']);

                $formFactory = new FormFactory();
                $form_fields = ModelSelector::getFormModel()->fetchFieldInfo($this->page);
                $form_info = ModelSelector::getFormModel()->fetchFormInfo($this->page);

                //====================================================================================================
                // profile picture - now clickable, opens the avatar modal instead of just displaying
                $user_container->addElement(new Image(
                    name: './img/authors/' . $aboutinfo['imgFileName'],
                    class: $styling_elements["img_class"] . ' clickable-avatar',
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
                    field_default_text: []
                );
                $left_container->addElement(new Title(
                    text: $aboutinfo['email'],
                    class: $styling_elements["email_class"],
                    attributes: [
                        'id' => 'userEmailDisplay'
                    ]
                ));
                $left_container->addElement(new Title(
                    text: "Create new article",
                    class: $styling_elements["new_article_title"]
                ));
                $left_container->addElement($form);

                //====================================================================================================
                // edit user info / password - now open modals instead of navigating away
                $left_container->addElement(new Title(
                    text: "Edit User information",
                    class: "fs-3 border-top mt-3"
                ));

                $left_container->addElement(new ButtonField(
                    type: "button",
                    name: 'Edit User Information',
                    class: 'btn btn-secondary mt-1',
                    label: 'Change user information',
                    id: $_SESSION['userID'] . '-edit-user-btn',
                    attributes: [
                        'data-bs-toggle' => 'modal',
                        'data-bs-target' => '#editUserModal'
                    ]
                ));

                $left_container->addElement(new ButtonField(
                    type: "button",
                    name: 'Edit Password',
                    class: 'btn btn-danger mt-1',
                    label: 'Change Password',
                    id: $_SESSION['userID'] . '-edit-pwd-btn',
                    attributes: [
                        'data-bs-toggle' => 'modal',
                        'data-bs-target' => '#editPasswordModal'
                    ]
                ));

                //====================================================================================================
                // modals - built with your existing FormFactory forms, just wrapped in Modal instead of a page
                $editUserForm = $formFactory->createForm(
                    form_info: ModelSelector::getFormModel()->fetchFormInfo('editUser'),
                    field_info: ModelSelector::getFormModel()->fetchFieldInfo('editUser'),
                    hidden_field_info: [
                        'page' => 'editUser',
                        'action' => 'updateUserInfo',
                        'id' => $_SESSION['userID']
                    ],
                    field_default_text: [
                        'name' => $aboutinfo['name'],
                        'email' => $aboutinfo['email'],
                    ]
                );
                $editUserModal = new Modal(id: 'editUserModal', title: 'Edit User Information');
                $editUserModal->addElement(new AtomicElement('<div id="editUserModal-errors" class="alert alert-danger d-none"></div>'));
                $editUserModal->addElement($editUserForm);

                $editPasswordForm = $formFactory->createForm(
                    form_info: ModelSelector::getFormModel()->fetchFormInfo('editPassword'),
                    field_info: ModelSelector::getFormModel()->fetchFieldInfo('editPassword'),
                    hidden_field_info: [
                        'page' => 'editPassword',
                        'action' => 'updatePassword',
                        'id' => $_SESSION['userID']
                    ],
                    field_default_text: []
                );
                $editPasswordModal = new Modal(id: 'editPasswordModal', title: 'Change Password');
                $editPasswordModal->addElement(new AtomicElement('<div id="editPasswordModal-errors" class="alert alert-danger d-none"></div>'));
                $editPasswordModal->addElement($editPasswordForm);

                // Could be funny to add as a special modal but not needed
                // -marius
                // $avatarModal = new Modal(id: 'avatarModal', title: 'Change Profile Picture');
                // $avatarModal->addElement(new AtomicElement(
                //     '<img id="avatar-preview" src="./img/authors/' . htmlspecialchars($aboutinfo['imgFileName']) . '" '
                //     . 'class="mb-2" style="max-width:150px;"><br>'
                //     . '<input type="file" id="avatar-input" name="avatar" accept="image/*" class="form-control" hidden>'
                //     . '<button type="button" class="btn btn-primary mt-2" onclick="document.getElementById(\'avatar-input\').click()">Choose photo</button>'
                //     . '<button type="submit" id="avatar-save-btn" class="btn btn-success mt-2">Save</button>'
                // ));

                $left_container->addElement($editUserModal);
                $left_container->addElement($editPasswordModal);
                //$left_container->addElement($avatarModal);

                //====================================================================================================
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
        $this->htmlpage->addToBodyContent(new AtomicElement("<br>"));
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
