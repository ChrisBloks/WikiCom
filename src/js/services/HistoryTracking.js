

export const HistoryTracking = {
    add(action, params=[]) {
        console.log('Adding History: ' + action);
        let data, unused, url;
        data = {'action': action};
        unused = "";
        url = "main.php?reqType=page&page=" ;

        switch (true) {
            case action == 'pageArticle':
                url += 'article&articleID=' + params[0];
                data.article_id = params[0];
                break;
            case action == 'pageEditArticle':
                url += 'editArticle&articleID=' + params[0];
                data.article_id = params[0];
                break;
            case action.substring(0,4) == 'page':
                url += action.slice(4).toLowerCase();
                break;
            case action == 'logout':
                url += 'home';
                break;
            default:
                url += 'home';
                unused = 'Unknown action in HistoryTracking!';
                break;
        }
        
        history.pushState(data, unused, url);
        // console.log(params[0]);
        // console.log('url: ', url);
        // console.log(history.state);
        
    }
}