// ============================================================
// ContactForm: AJAX contact form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";


export const EditArticleForm = {
    init() {
    console.log("Initing EditArticleForm");
    AjaxForms.bind("#edit_article_form", {
      onSuccess: function (_) {
        console.log('Succes!');
        // Go to login page
      },
    });
  },
};
