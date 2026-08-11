<?php
// common parameters:
// Todo: save all commands as string and loop
// is instance of: checken voor interface class

//$isloggedIn;
//$page_value;
//$article_id;
//$user_ids;
//$tag_ids; 
//$sortBy;

class PageFactory
{
    private array $pagecontainer = [];


    public function getElementsByPage($page_value)
    {
        $form_info = 1;
        $field_info = 1;
        $rating = 1;
        $article_info = 1;
        $articleSearchResult = 1;

        $pagecontainer = [];
        $pagecontainer[] = 'Header()';


        // Per page decision for htmlelements
        //$menu_items = websiteModel->getMenuItems(bool $isloggedIn) 
        $pagecontainer[] = 'Menu($menu_items)';
        // terrot
        // tnotice
        switch ($page_value) {
            case 'home':
            case 'about':
                //$bodytext = websiteModel->getBodyText($page_value,$user_id='')
                $pagecontainer[] = 'Text($bodytext)';
                break;
            case 'contact':
            case 'login':
            case 'register':
                //$field_info = websiteModel->getFieldInfo($page_value)
                //$form_info = websiteModel->getFormInfo($page_value)
                if ($field_info !== false AND $form_info !== false) {
                    $pagecontainer[] = 'Form($field_info, $form_info)';
                }
                break;
            case 'article':
                //$rating = fetchAvgRating($article_id);
                //$article_info = fetchArticleById($article_id);
                if ($rating !== false AND $article_info !== false) {
                    $pagecontainer[] = 'MainArticle(rating, $article_info["user"] ,author,article_title,article_summary)';
                } else {
                    // collect error to controller
                }
                break;
            case 'search':
                //$field_info = websiteModel->getFieldInfo($page_value);
                //$form_info = websiteModel->getFormInfo($page_value);
                if ($field_info !== false AND $form_info !== false) {
                    $pagecontainer[] = 'Form(field-info, form-info)';
                    //$articleSearchResult = fetchArticlesBySearch($user_ids, $tag_ids, $sortBy)
                    if ($articleSearchResult !== false) {
                        $pagecontainer[] = 'ArticleList(authors,tags,sortby,article_title,article_summary,rating,lastEdit)';
                    } else {
                        // collect error
                    }
                }
                break;
            case 'dashboard':
                //$articleSearchResult = fetchArticleByUserId($user_id);
                if ($articleSearchResult !== false) {
                    $pagecontainer[] = 'ArticleList(author,tags,article_title,article_summary,rating,lastEdit)';
                } else {
                    // collect error
                }
                break;
            case 'editArticle':
                //$rating = fetchAvgRating($article_id);
                //$article_info = fetchArticleById($article_id);
                if ($rating !== false AND $article_info !== false) {
                    $pagecontainer[] = 'MainArticle(rating,user,author,article_title,article_summary)'; //if editpage use form
                    //$field_info = websiteModel->getFieldInfo($page_value);
                    //$form_info = websiteModel->getFormInfo($page_value);}
                    if ($field_info !== false AND $form_info !== false) {
                        $pagecontainer[] = 'Form(field-info, form-info)';
                    }
                }
                break;

        }
        $mainbody = 'Main($pagecontainer[])';
        $pagecontainer[] = 'Footer';
        return $pagecontainer;



    }
}

$pagearray = ['home', 'about', 'contact', 'login', 'register', 'article', 'search', 'dashboard', 'editArticle'];


$test = new PageFactory();
foreach ($pagearray as $page_value) {
    echo $page_value . ':' . PHP_EOL;
    print_r($test->getElementsByPage($page_value));
    echo '|||||||';
    echo PHP_EOL;
}