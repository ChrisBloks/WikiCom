import { ajaxGET } from "./Ajax.js";
import { ArticleDelete } from "../components/ArticleDelete.js"
import { TagWidget } from "../components/Tagwidget.js";
import { initPasswordLengthChecker } from "../components/PasswordLengthChecker.js";
import { Toasts } from "../components/Toasts.js";
import { MarkdownToolbar } from "../components/MarkdownEditor.js";
import { saveRating } from "../services/SaveRating.js";
import { SearchForm } from "../components/searchForm.js";

export const Router = {
  init() {
    const nav_items = document.querySelectorAll('a.nav-link');
    nav_items.forEach(
      function(nav_item) {
        nav_item.addEventListener(
          "click",
          async (event) => {
            // remove parent node once we've removed the <a> part
            const page = event.target.parentElement.dataset.targetPage;
            if (!page) return;

            event.preventDefault();
            console.log("menu clicked!");
            console.log(page);

            await ajaxGET(
              "main.php?action=" + page,
              "json",
              (response) => {
                if (!Array.isArray(response)) return;
                console.log("response reached")
                response.forEach(({ selector, content }) => {
                  // console.log(selector);
                  // console.log(content);
                  try {
                    document.querySelector(selector).innerHTML = content;
                  } catch (e) {
                    alert(`Skipping "${selector}":`, e.message);
                  }
                });

                // Init the menu again
                Router.init();
              },
              (error) => console.error("Content unable to be loaded:", error),
            );
          }
        )
      }
    );
  },
};
