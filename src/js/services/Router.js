import { ResponseHandler } from "../responseHandler.js";
import { ajaxGET } from "./Ajax.js";

export const Router = {
  // ==============================================================================
  init() {
    const nav_items = document.querySelectorAll('a.nav-link');
    nav_items.forEach(
      function (nav_item) {
        if (nav_item.classList.contains('wiki-initialized')) return;
        else {
          addMenuListener(nav_item);
          nav_item.classList.add('wiki-initialized');
        }
      }
    );
  },
  // ==============================================================================
  addMenuListener(nav_item) {
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
          "main.php?reqType=async&action=" + page,
          "json",
          (response) => {
            if (!Array.isArray(response)) return;
            console.log("response reached")
            ResponseHandler.handleResponse(response);
          },
          (error) => console.error("Content unable to be loaded:", error),
        );
      }
    )
  }
  // ==============================================================================
}
