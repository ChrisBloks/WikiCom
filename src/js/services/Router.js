import { ajaxGET } from "./Ajax.js";

export const Router = {
  init() {
    document.addEventListener("click", async (event) => {
      const link = event.target.closest(".nav-link a, a.nav-link");
      if (!link) return;

      event.preventDefault();
      console.log("menu clicked!");
      console.log(link.href);

      await ajaxGET(
        link.href,
        "json",
        (response) => {
          if (!Array.isArray(response)) return;

          response.forEach(({ selector, content }) => {
            try {
              document.querySelectorAll(selector).innerHTML = content;
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
