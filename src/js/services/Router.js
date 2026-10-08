import { ajaxPOST } from "./Ajax.js";

export const Router = {
  init() {
    document.addEventListener("click", async (event) => {
      const page = event.target.dataset.targetPage;
      console.log(event.target);
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
