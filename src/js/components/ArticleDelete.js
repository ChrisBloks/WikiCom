// ============================================================
// ArticleDelete: AJAX delete for article rows
// ============================================================
import { ajaxPOST } from "../services/Ajax.js";

export const ArticleDelete = {
  init() {
    document.querySelectorAll('.ajax-delete-button').forEach((button) => {


      // on submit
      button.addEventListener("click", async (event) => {
        // if it doesnt have the delete form tag -> return
        // if (!event.target.matches(".ajax-delete-form")) return;
        // event.preventDefault();
        console.log(button.dataset.articleId);
        // get vars
        let articleId = button.dataset.articleId


        // Popup for user confimration
        if (!confirm("Are you sure you want to delete this article?")) return;

        // start ASYNC
        await ajaxPOST(
          "main.php?reqType=ajax",
          "json",
          { action: "deleteArticle", id: articleId },
          (response) => {
            if (!response.success) {
              // if response.success is false or not set, return error
              alert(response.message || "Could not delete the article.");
              return;
            }

            // else -> animation to remove row from the table
            console.log("success!");
            const row = button.closest("tr");
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
    });

  },
};