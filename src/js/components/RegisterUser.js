import { AjaxForms } from "../services/AjaxForms.js";
import { Toasts } from "../components/Toasts.js";

export const RegisterUser = {
  init() {
    console.log("Initing RegisterUser");
    AjaxForms.bind(".ajax-userRegister-form", {
      onSuccess: function (result) {
        console.log('Succes!');
        Toasts.show("message", "Yippieeee");
      },
    });
  },
};
