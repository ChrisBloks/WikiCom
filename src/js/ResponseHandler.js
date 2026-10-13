import { Router } from "./services/Router.js";
import { SearchForm } from "./components/SearchForm.js";
import { ContactForm } from "./components/ContactForm.js";
import { RegisterUser } from "./components/RegisterUser.js";
import { LoginForm } from "./components/LoginForm.js";
import { EditArticleForm } from "./components/EditArticle.js";
import { DashboardEvents } from "./components/DashboardEvents.js";
import { TagWidget } from "./components/Tagwidget.js";
import { updateCheckboxGroup, resetCheckboxesToDefault, } from "./components/Checkboxes.js";
import { saveRating } from "./services/SaveRating.js";



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
                    ContactForm.init();
                    console.log("Contact form initialized");
                    break;
                case 'pageRegister':
                    RegisterUser.init();
                    console.log("Register form initialized");
                    break;
                case 'pageLogin':
                    LoginForm.init();
                    console.log("Login form initialized");
                    break;
                case 'pageSearch':
                    //tag searchbar
                    document.querySelectorAll(".checkbox_group").forEach(function (checkbox_group) {
                        resetCheckboxesToDefault(checkbox_group);
                    });

                    // searchbar update (js)
                    document.querySelectorAll(".searchField").forEach(function (search_field) {
                        search_field.addEventListener("input", function (_) {
                            console.log("input change");
                            let checkbox_group = this.parentNode.querySelector(".checkbox_group");
                            updateCheckboxGroup(checkbox_group, this.value, 1);
                        });
                    });
                    SearchForm.init();
                    console.log('Search form initialized');
                    break;
                case 'pageDashboard':
                    DashboardEvents.init();
                    console.log("Dashboard events initialized")
                    break;
                case 'pageArticle':
                    // scales stars correctly
                    const fillSpan = document.querySelector(".fill-ratings span");
                    const starRatings = document.querySelector(".star-ratings");
                    if (fillSpan && starRatings) {
                        starRatings.style.width = fillSpan.offsetWidth + "px";
                    }
                    saveRating.init();
                    break;
                case 'pageEditArticle':
                    // editArticle add tag widget
                    TagWidget.init();
                    const widget = document.querySelector("#add-tag-widget");
                    const checkboxgroup = document.querySelector(".checkbox_group");
                    if (widget && checkboxgroup) {
                        checkboxgroup.parentNode.insertBefore(widget, checkboxgroup);
                    }

                    //tag searchbar
                    document.querySelectorAll(".checkbox_group").forEach(function (checkbox_group) {
                        resetCheckboxesToDefault(checkbox_group);
                    });

                    // searchbar update (js)
                    document.querySelectorAll(".searchField").forEach(function (search_field) {
                        search_field.addEventListener("input", function (_) {
                            console.log("input change");
                            let checkbox_group = this.parentNode.querySelector(".checkbox_group");
                            updateCheckboxGroup(checkbox_group, this.value, 1);
                        });
                    });
                    EditArticleForm.init()
                    console.log('Edit article form initialized');
                    break;

                default:
                    console.log('ResponseHandler error, status: ' + status + ' not recognized.');
                    break;
            }
        }

    },
    // ==============================================================================
}