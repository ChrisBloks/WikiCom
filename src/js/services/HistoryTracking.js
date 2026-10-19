export const HistoryTracking = {
    init() {
        let url,
            url_params,
            params = [],
            current_page;

        url = window.location.href.split("&page=");

        if (url.length == 1) {
            // main.php was called without parameters
            HistoryTracking.add("pageHome", params, true);
        } 
        else {
            // params.lenght >= 2
            url_params = url[1].split("&");
            current_page = url_params[0];
            if (current_page in ["article, editArticle"]) {
                articleID = url_params[1].split("=")[1];
                params = [parseInt(articleID)];
            }
            HistoryTracking.add("page"+current_page, params, true);
        }
    },
    add(action, params = [], replace = false) {
        console.log("Adding History: " + action);
        let data, unused, url;
        data = { action: action };
        unused = "";
        url = "main.php?reqType=page&page=";

        switch (true) {
            case action == "pageArticle":
                url += "article&articleID=" + params[0];
                data.article_id = params[0];
                break;
            case action == "pageEditArticle":
                url += "editArticle&articleID=" + params[0];
                data.article_id = params[0];
                break;
            case action.substring(0, 4) == "page":
                url += action.slice(4).toLowerCase();
                break;
            case action == "logout":
                url += "home";
                break;
            default:
                url += "home";
                unused = "Unknown action in HistoryTracking!";
                break;
        }

        if (replace) {
            history.replaceState(data, unused, url);
        } else {
            history.pushState(data, unused, url);
        }

        // console.log(params[0]);
        // console.log('url: ', url);
        // console.log(history.state);
    },
};
