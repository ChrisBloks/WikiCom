// ============================================================
// LoginForm: AJAX login form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";
import { ResponseHandler } from "../ResponseHandler.js";


export const LoginForm = {
    init() {
    console.log("Initing LoginForm");
    AjaxForms.bind("#login_form", {
      onSuccess: function (response) {
        ResponseHandler.handleResponse(response.html);
        // Go to home page with user logged in.
      },
    });
  },
};
