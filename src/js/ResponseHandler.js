import { Router } from "./services/Router";


export const ResponseHandler = {
    // ==============================================================================
    // ASSUMPTION: response is an array of [[selector, content, status], [selector, content, status], ...]
    handleResponse(response) {
        response.forEach(element => this.handle(element));
        // Add menu eventListeners
        Router.init();
    },
    // ==============================================================================
    // ASSUMPTION: item = [selector, content, status];
    handle(item) {
        let selector, content, status;
        [selector, content, status] = item;

        // place content on page
        try {
            document.querySelector(selector).innerHTML = content;
        } catch (e) {
            console.log(`Skipping "${selector}":`, e.message);
        }

        // Handle status (add EventListeners)
        switch (status) {
            case 'pageHome':
            case 'pageAbout':
                break; // Do nothing
            case 'pageContact':
                // pageContact
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
    },
    // ==============================================================================
}