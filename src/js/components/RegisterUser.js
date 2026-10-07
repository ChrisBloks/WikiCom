import { AjaxForms } from "../services/AjaxForms.js";
import { Toasts } from "../components/Toasts.js";

export const RegisterUser = {
  init() {
    console.log("Initing RegisterUser");
    AjaxForms.bind("#register_user_form", {
      onSuccess: function (_) {
        console.log('Succes!');
        Toasts.show("message", "Yippieeee");
        // Go to login page
      },
    });
  },
};
