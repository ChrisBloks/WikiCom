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

use ArrayAccess;
use Wiki\tools\utils\HtmlUtils,
    Wiki\tools\traits\tErrorMessageCollector,
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
        $main->addElement(new NoticeMessage());
        $main->addElement(new AtomicElement(new ElementInfo(["text" => "<br>"])));

        // Maybe seperate controller
        $elements_info = ModelSelector::getElementModel()->fetchPageElements($this->page);


        $this->add_data_to_elements($elements_info);


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


    // TODO: Clean up this function
    protected function add_data_to_elements(array &$elements_info)
    {
        foreach ($elements_info as &$element_info) {

            if (isset($element_info['sub_fields'])) {
                $this->add_data_to_elements($element_info['sub_fields']);
            }

            if ($this->response['page'] == 'article' || $this->response['page'] == 'editArticle'){
                if(!isset($this->article_info)){
                    $this->article_info = ModelSelector::getArticleModel()
                        ->fetchArticleByID($this->response['articleID'] ?? $this->response['editArticleID'] ?? []);
                }
            }

            switch (true) {
                case $element_info['element_name'] === "random_article":
                    $excludelist = $excludelist ?? [];
                    $element_info['article'] = ModelSelector::getArticleModel()
                        ->fetchFrontPageArticles($excludelist);
                    $excludelist[] = $element_info['article']['id'];
                    break;
                case str_contains($element_info['element_name'], 'about_'):
                case str_contains($element_info['element_name'], 'dashboard_'):
                    $userID = ((isset($this->response['aboutID'])) ? $this->response['aboutID'] : $_SESSION['userID']);
                    $about_info = ModelSelector::getUserInfoModel()->fetchUserInfoById($userID);
                    $element_info['title'] = $about_info['name'];
                    $element_info['bodytext'] = $about_info['description'];
                    $element_info['image'] = $about_info['imgFileName'];
                    if (str_contains($element_info['element_name'], 'email')) {
                        $element_info['title'] = $about_info['email'];
                    }
                    break;
                // Check if the value for this hidden field should be in the response.
                case $element_info['php_class'] == 'EditableArticle':
                    $this->article_info['tags'] = ModelSelector::getArticleModel()
                        ->fetchArticleTags($this->response['editArticleID']);
                    $element_info['article_info'] = $this->article_info;
                    break;
                case $element_info['php_class'] == 'HiddenField':
                    $value = $element_info['field_info']['value'];
                    if ($value && $value[0] == '$') {
                        $key = substr($value, 1);
                        $element_info['field_info']['value'] = ($this->response[$key] ?? false);
                    }
                    if ($value && $value[0] == '#') {
                        $key = substr($value, 1);
                        $element_info['field_info']['value'] = ($_SESSION[$key] ?? false);
                    }
                    
                    break;
                case ($element_info['php_class'] == 'DialogueButton'):
                    $element_info['attributes'] = ModelSelector::getElementModel()
                        ->fetchDialogueAttributesByElementId($element_info['element_id']);
                    if (str_contains($element_info['element_name'], 'edit_user')) {
                        $element_info['html_id'] = $_SESSION['userID'] . $element_info['html_id'];
                    }
                    break;
                case $element_info['element_name'] == "table_dashboard":
                    $element_info['options_info'][] = ModelSelector::getArticleModel()
                        ->fetchArticleByUserId($_SESSION['userID']);
                break;
                case $element_info['element_name'] == "edit_user_form":
                    $about_info = ModelSelector::getUserInfoModel()->fetchUserInfoById($_SESSION['userID']);
                    foreach ($element_info['sub_fields'] as &$sub_field){
                        if ($sub_field['field_info']['type'] != 'hidden'){
                        $sub_field['field_info']['value'] = $about_info[$sub_field['field_info']['field_name']] ?? "";
                        }
                    }
                    unset($sub_field);
                    break;
                case $element_info['php_class'] == "TagButtonContainer":
                    $element_info['options_info'][0] = ModelSelector::getArticleModel()
                        ->fetchArticleTags($this->response['articleID']);
                    break;

                case $element_info['element_name'] == 'article_text_img_div':
                    foreach($element_info['sub_fields'] as $sub_element_info){
                        if($sub_element_info['element_name'] == 'article_body_text'){
                            $sub_element_info['text'] = $this->article_info['summary'];
                        }
                    }
                    
                    break;
                case $element_info['element_name'] == "article_code_block":
                    $element_info['text'] = $this->article_info['codeBlock'];
                    break;
                case $element_info['element_name'] == "article_title":
                    $element_info['text'] = $this->article_info['title'];
                    break;
                case $element_info['element_name'] == "article_author_name":
                    $element_info['text'] = $this->article_info['name'];
                    break;
                case $element_info['element_name'] == "article_body_img":
                    $element_info['image'] = \CONFIG::ARTICLEIMGPATH . $this->article_info['imgFileName'];
                    break;
                case $element_info['element_name'] == "rating_div":
                    $element_info['options_info']['rating'] = $this->article_info['rating'];
                    $element_info['options_info']['count'] = $this->article_info['n_ratings'];
                    if($this->response['isLoggedIn']){
                        $element_info['options_info']['ratable'] = True;
                    }
                    $element_info['options_info']['article_id'] = $this->response['articleID'];
                    break;
                default:
                    break;
            }
        }
        unset($element_info);
    }
}
