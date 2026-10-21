import { ResponseHandler } from "../ResponseHandler.js";
import { stringFunc } from "../utils/stringFunc.js";
import { ajaxGET } from "./Ajax.js";


export const HistoryNavigation = {
    init(){
        window.addEventListener(
            'popstate',
             async (event) => {
                let action, url;
                
                if(!event.state){ // There is no history
                    return;
                }

                action = event.state.action;

                if(!action){ // The top state of history has no action attached to it
                    return;
                }

                if(!action.substring(0,4) == 'page'){ // The top state of history is not a navigation command
                    return;
                }

                action = action.slice(4); //.toLowerCase(); // Extract page target
                action = stringFunc.lowerCaseFirst(action);
                url = window.location.href.split('main')[0]; // Extract local URL 
                url += "main.php?reqType=page&page=" + action // Attach the server request
                

                if (action == 'article' || action == 'editArticle'){ // if the target action is an article, require an articleID
                    url += '&articleID=' + event.state.article_id;
                }
                navigation.navigate(url);
            
            }
        );
    }
}