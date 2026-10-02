// ============================================================
// Bootstrap Toasts —
// ============================================================
export const Toasts = {
  show(type, text) {
    const bgMap = { error: "text-bg-danger", message: "text-bg-success" };
    const bgClass = bgMap[type] || "text-bg-secondary";
    const label = type.charAt(0).toUpperCase() + type.slice(1);

    // create wrapper toast
    const toast = document.createElement("div");
    toast.className = `toast ${bgClass}`;
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");

    // create toast header element
    const header = document.createElement("div");
    header.className = "toast-header";

    // create toast title
    const strong = document.createElement("strong");
    strong.className = "me-auto";
    strong.textContent = label;

    // toast timestamp
    const small = document.createElement("small");
    small.className = "toast-timestamp";
    small.textContent = "just now";

    // close button for toast
    const closeBtn = document.createElement("button");
    closeBtn.type = "button";
    closeBtn.className = "btn-close toast-button";
    closeBtn.setAttribute("data-bs-dismiss", "toast");
    closeBtn.setAttribute("aria-label", "Close");

    header.append(strong, small, closeBtn);

    // create toast body
    const body = document.createElement("div");
    body.className = "toast-body";
    body.textContent = text;

    toast.append(header, body);

    document.querySelector("#toast-container").append(toast);
    this._activate(toast);
  },

  // initialize toasts
  initExisting() {
    document.querySelectorAll(".toast").forEach((el) => this._activate(el));
  },

  // private function - initializes the toast and auto-hides/removes itself
  _activate(toast) {
    const toastElement = new bootstrap.Toast(toast, {
      autohide: true,
      delay: 5000,
    });
    toast.addEventListener("hidden.bs.toast", function () {
      this.remove();
    });
    toastElement.show();
  },
};
