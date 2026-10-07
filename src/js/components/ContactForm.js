// ============================================================
// ContactForm: AJAX contact form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";


export const ContactForm = {
    init() {
        // contact form ajax
        AjaxForms.bind("#contact_form", {
            onSuccess: function (result) {
                
            }
        });
    }
}