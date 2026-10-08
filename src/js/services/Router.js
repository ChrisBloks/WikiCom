import { ajaxPOST } from "./Ajax.js";

export const Router = {
  init() {
    document.addEventListener("click", async (event) => {
      // remove parent node once we've removed the <a> part
      const page = event.target.parentElement.dataset.targetPage;
      if (!page) return;

      event.preventDefault();
      console.log("menu clicked!");
      console.log(page);

      await ajaxPOST(
        "main.php",
        "json",
        {action: 'goToPage', page: page},
        (response) => {
          if (!Array.isArray(response)) return;
          console.log("response reached")
          response.forEach(({ selector, content }) => {
            console.log(selector);
            console.log(content);
            try {
              document.querySelector(selector).innerHTML = content;
            } catch (e) {
              alert(`Skipping "${selector}":`, e.message);
            }
          });
        },
        (error) => console.error("Content unable to be loaded:", error),
      );
    });
  },
};
