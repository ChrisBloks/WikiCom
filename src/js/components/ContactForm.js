// ============================================================
// ContactForm: AJAX contact form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";
import { Toasts } from "../components/Toasts.js";


export const ContactForm = {
    init() {
    console.log("Initing ContactForm");
    AjaxForms.bind("#contact_form", {
      onSuccess: function (_) {
        console.log('Succes!');

      },
    });
  },
};
