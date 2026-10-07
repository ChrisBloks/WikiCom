// ============================================================
// ContactForm: AJAX contact form handling
// ============================================================
import { ajaxPOST } from "../services/Ajax.js";
import { EmailValidator } from "./EmailValidator.js";
import { Toasts } from "../components/Toasts.js";


export const ContactForm = {
    init() {
        let form = document.querySelector("#contact_form");
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            let name = document.querySelector("#name_input");
            let email = document.querySelector("#email_input");
            let message = document.querySelector("#message_input");
            let valid = true;
            if (!name.value) {
                Toasts.show("error", "Name is required.");
                valid = false;
            }
            if (!email.value) {
                Toasts.show("error", "Email is required.");
                valid = false;
            }
            else if (!EmailValidator(email.value)) {
                Toasts.show("error", "Email not valid.");
                valid = false;
            }
            if (!message.value) {
                Toasts.show("error", "Message is required.");
                valid = false;
            }
            if (!valid) {
                return;
            }
            else {
                // contact form ajax
                ajaxPOST(
                    "main.php",
                    "json",
                    new FormData(form),
                    (result) => {
                        if (result.success) {
                            // show result messages in toasts
                            Toasts.show("message", result.message);
                        } else {
                            if (result.errors?.length == 0) {
                                Toasts.show("error", "Something went wrong.");
                            } else {
                                result.errors?.forEach((msg) => Toasts.show("error", msg));
                            }
                        }
                    },
                    (error) => {
                        console.error(error);
                        Toasts.show("error", "Request failed, please try again.");
                    },
                )
            }
        })



    }
}