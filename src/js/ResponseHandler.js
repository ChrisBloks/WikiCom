import { ContactForm } from "./components/ContactForm.js";
import { Router } from "./services/Router.js";


export const ResponseHandler = {
    // ==============================================================================
    // ASSUMPTION: response is an array of [[selector, content, status], [selector, content, status], ...]
    handleResponse(response) {
        response.forEach(element => ResponseHandler._handle(element));
        // Add menu eventListeners
        Router.init();
    },
    // ==============================================================================
    _handle(item) {
        let selector, content, status;
        selector = item['selector'] ?? false;
        content = item['content'] ?? false;
        status = item['status'] ?? false;

        if (!(selector && content)) {
            console.log(item + ' is an invalid entry to ResponseHandler.item');
            return;
        }
        // Throw an error.

        // place content on page
        try {
            document.querySelector(selector).innerHTML = content;
        } catch (e) {
            console.log(`Skipping "${selector}":`, e.message);
        }

        // Handle status (add EventListeners)
        if (status) {
            switch (status) {
                case 'pageHome':
                case 'pageAbout':
                    break; // Do nothing
                case 'pageContact':
                    // ContactForm.init();
                    break;
                case 'pageRegister':
                    break;
                case 'pageLogin':
                    break;
                case 'pageDashboard':
                    break;
                case 'pageArticle':
                    break;
                case 'pageEditArticle':
                    break;
                
                default:
                    console.log('ResponseHandler error, status: ' + status + ' not recognized.');
                    break;
            }
        }
        
    },
    // ==============================================================================
}