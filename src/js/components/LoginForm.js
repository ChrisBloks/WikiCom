// ============================================================
// LoginForm: AJAX login form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";
import { Toasts } from "./Toasts.js";


export const LoginForm = {
    init() {
    console.log("Initing LoginForm");
    AjaxForms.bind("#login_form", {
      onSuccess: function (_) {
        console.log('Succes!');
        // Go to home page with user logged in.
      },
    });
  },
};
