// ============================================================
// Binds events based on the page
// ============================================================
import { SearchForm } from "../components/SearchForm.js";
import { ContactForm } from "../components/ContactForm.js";
import { RegisterUser } from "../components/RegisterUser.js";
import { LoginForm } from "../components/LoginForm.js";
import { EditArticleForm } from "../components/EditArticle.js";
import { DashboardEvents } from "../components/DashboardEvents.js";
import { TagWidget } from "../components/Tagwidget.js";
import { updateCheckboxGroup, resetCheckboxesToDefault, } from "../components/Checkboxes.js";
import { saveRating } from "../services/SaveRating.js";

export function EventBinder(page) {

    if (page) {
        switch (page) {
            case 'home':
            case 'about':
                break; // Do nothing
            case 'contact':
                ContactForm.init();
                console.log("Contact form initialized");
                break;
            case 'register':
                RegisterUser.init();
                console.log("Register form initialized");
                break;
            case 'login':
                LoginForm.init();
                console.log("Login form initialized");
                break;
            case 'search':
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
            case 'dashboard':
                DashboardEvents.init();
                console.log("Dashboard events initialized")
                break;
            case 'article':
                // scales stars correctly
                const fillSpan = document.querySelector(".fill-ratings span");
                const starRatings = document.querySelector(".star-ratings");
                if (fillSpan && starRatings) {
                    starRatings.style.width = fillSpan.offsetWidth + "px";
                }
                saveRating.init();
                break;
            case 'editarticle':
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

}