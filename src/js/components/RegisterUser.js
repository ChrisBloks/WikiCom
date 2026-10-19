import { AjaxForms } from "../services/AjaxForms.js";
import { Toasts } from "../components/Toasts.js";
import { ResponseHandler } from "../ResponseHandler.js";

export const RegisterUser = {
  init() {
    console.log("Initing RegisterUser");
    AjaxForms.bind("#register_user_form", {
      onSuccess: function (response) {
        console.log('Succes!');
        Toasts.show("message", "Yippieeee");
        ResponseHandler.handleResponse(response.html);
        // Go to login page
      },
    });
  },
};
