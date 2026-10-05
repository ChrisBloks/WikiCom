// ============================================================
// ArticleDelete: AJAX delete for article rows
// ============================================================
import { ajaxPOST } from "../services/Ajax.js";


export const ArticleDelete = {
  init() {
    // on submit
    document.addEventListener("submit", async (event) => {
      // if it doesnt have the delete form tag -> return
      if (!event.target.matches(".ajax-delete-form")) return;
      event.preventDefault();

      // get vars
      const form = event.target;
      const articleId = form.querySelector('input[name="id"]').value;

      // Popup for user confimration
      if (!confirm("Are you sure you want to delete this article?")) return;

      // start ASYNC
      await ajaxPOST(
        "main.php",
        "json",
        { action: "deleteArticle", id: articleId },
        (response) => {
          if (!response.success) {
            // if response.success is false or not set, return error
            alert(response.message || "Could not delete the article.");
            return;
          }

          // else -> animation to remove row from the table
          const row = form.closest("tr");
          if (!row) return;
          row.style.transition = "opacity 0.2s";
          row.style.opacity = "0";
          setTimeout(() => row.remove(), 200);
        },
        (error) => {
          // if error -> log console
          console.error(error);
          alert("Request failed, please try again.");
        },
      );
    });
  },
};