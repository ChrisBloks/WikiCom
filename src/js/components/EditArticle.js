// ============================================================
// ContactForm: AJAX contact form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";
import { ResponseHandler} from "../ResponseHandler.js" 


export const EditArticleForm = {
    init() {
    console.log("Initing EditArticleForm");
    AjaxForms.bind("#edit_article_form", {
      onSuccess: function (response) {
        ResponseHandler.handleResponse(response.html);
      },
    });
  },
};
