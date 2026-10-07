// ============================================================
// AjaxForms: generic form handling
// ============================================================
// requires toasts to be active on the page

// import { toasts } from "../components/Toasts.js";
import { ajaxPOST } from "./Ajax.js";
import { Toasts } from "../components/Toasts.js";

export const AjaxForms = {
  // starts a spinner on the submit button on a form
  setLoading(button, isLoading) {
    const spinner = button.querySelector(".spinner-border");
    spinner?.classList.toggle("d-none", !isLoading);
    button.disabled = isLoading;
  },

  // bind the form to the page
  bind(formSelector, options = {}) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    // get vars
    const modal = form.closest(".modal");
    const errorBox = modal ? modal.querySelector('[id$="-errors"]') : null;

    // on submit:
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      // disable submit button and start loading thingy
      const submitBtn = form.querySelector('[type="submit"]');
      this.setLoading(submitBtn, true);
      errorBox?.classList.add("d-none");

      // start fetch()
      try {
        await ajaxPOST(
          "main.php",
          "json",
          new FormData(form),
          (result) => {
            if (result.success) {
              // show result messages in toasts
              Toasts.show("message", result.message);
              options.onSuccess?.(result);
              if (modal) bootstrap.Modal.getInstance(modal)?.hide();
            } else if (errorBox) {
              // if the response has errors, display them too
              const messages = result.errors?.length
                ? result.errors
                : [result.message];

              errorBox.innerHTML = messages
                .map((msg) => `<div>${msg}</div>`)
                .join("");

              errorBox.classList.remove("d-none");
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
        );
      } finally {
        this.setLoading(submitBtn, false);
      }
    });
  },
};