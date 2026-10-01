// ============================================================
// Bootstrap Toasts —
// ============================================================
const Toasts = {
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

// ============================================================
// ajaxPOST frame for making ajax requests through POST
// ============================================================
async function ajaxPOST(url, response_type, data, success, fail) {
  let result;

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" },
      // URLSearchParams: urlencoded, fills $_POST because JS can't by itself
      // jquery does this for you so we have to do it manually
      // this is also probably not the correct way to do it:
      // https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams
      body: data instanceof FormData ? data : new URLSearchParams(data),
    });

    if (!response.ok) {
      return fail(await response.text());
    }

    // if its json, use json. Otherwise text. Might need XML later?
    result =
      response_type === "json" ? await response.json() : await response.text();
  } catch (error) {
    // network failure OR invalid JSON from the server
    return fail(error.message || "Network error");
  }

  success(result);
}

// ============================================================
// AjaxForms: generic form handling
// ============================================================
const AjaxForms = {
  // starts a spinner on the submit button on a form
  setLoading(button, isLoading) {
    const spinner = button.querySelector(".spinner-border");
    spinner?.classList.toggle("d-none", !isLoading);
    button.disabled = isLoading;
  },

  // binf the form for the call
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

              const div = document.createElement("div");
              errorBox.innerHTML = messages
                .map((msg) => `<div>${msg}</div>`)
                .join("");

              errorBox.classList.remove("d-none");
            } else {
              Toasts.show("error", result.message || "Something went wrong.");
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

// ============================================================
// ArticleDelete: AJAX delete for article rows
// ============================================================
const ArticleDelete = {
  init() {
    // on submit
    document.addEventListener("submit", async (event) => {
      // if it doesnt have the delete form tag -> return
      if (!event.target.matches(".ajax-delete-form")) return;
      event.preventDefault();

      // get vars
      const form = event.target;
      const articleId = form.querySelector('input[name="id"]').value;

      // Popup for user confimration
      if (!confirm("Are you sure you want to delete this article?")) return;

      // start ASYNC
      await ajaxPOST(
        "main.php",
        "json",
        { action: "deleteArticle", id: articleId },
        (response) => {
          if (!response.success) {
            // if response.success is false or not set, return error
            alert(response.message || "Could not delete the article.");
            return;
          }

          // else -> animation to remove row from the table
          const row = form.closest("tr");
          if (!row) return;
          row.style.transition = "opacity 0.2s";
          row.style.opacity = "0";
          setTimeout(() => row.remove(), 200);
        },
        (error) => {
          // if error -> log console
          console.error(error);
          alert("Request failed, please try again.");
        },
      );
    });
  },
};

// ============================================================
// TagWidget —
// ============================================================
const TagWidget = {
  init() {
    const nameInput = document.getElementById("new-tag-name");
    const addBtn = document.getElementById("add-tag-btn");
    if (!nameInput || !addBtn) return;

    nameInput.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById("add-tag-btn").click();
      }
    });
    addBtn.addEventListener("click", () => this._addTag());
  },

  // -priavte func, adds the tag to the div
  _addTag() {
    const tagName = document.getElementById("new-tag-name").value.trim();
    if (!tagName) return;
    let alreadyAdded = false;

    document
      .querySelectorAll(".checkbox_group label")
      .forEach(function (label) {
        if (label.textContent.trim().toLowerCase() === tagName.toLowerCase()) {
          alreadyAdded = true;
        }
      });
    if (alreadyAdded) {
      alert("That tag is already in the list.");
      return;
    }

    const safeId = tagName.toLowerCase().replace(/[^a-z0-9]+/g, "-");
    const checkboxHtml =
      `<input type="checkbox" name="existing_tag[${safeId}]" id="new-tag-${safeId}" class="Existing-tag form-check-input" value="0" checked>` +
      `<label for="new-tag-${safeId}">${escapeHtml(tagName)}</label><br>`;

    document
      .querySelector(".checkbox_group")
      .insertAdjacentHTML("beforeend", checkboxHtml);
    const input = document.getElementById("new-tag-name");
    input.value = "";
    input.focus();
  },
};

// ============================================================
// PasswordLengthChecker — min-length validation
// checks length of password 1
// ============================================================
function initPasswordLengthChecker() {
  // get password
  const passwordInput = document.querySelector("#newpassword-1");

  if (!passwordInput) return;
  // get length
  const minLength = parseInt(passwordInput.dataset.minLength, 10) || 4;

  function showError(input, message) {
    const existing = input.nextElementSibling;
    if (existing && existing.classList.contains("feedback")) {
      existing.remove();
    }
    if (message) {
      input.insertAdjacentHTML(
        "afterend",
        `<div class="feedback error text-danger">${message}</div>`,
      );
    }
  }

  function validatePasswordLength() {
    if (passwordInput.length > 0 && passwordInput.length < minLength) {
      showError(
        document.querySelector("#newpassword-1"),
        `Password must be at least ${minLength} characters.`,
      );
      return false;
    }
    showError(passwordInput, "");
    return true;
  }

  document
    .querySelectorAll("#newpassword-1, #newpassword-2")
    .forEach(function (el) {
      el.addEventListener("input", validatePasswordLength);
    });

  // call function on submit password
  document.querySelector(".edit-password")?.addEventListener("submit", (e) => {
    if (!validatePasswordLength()) e.preventDefault();
  });
}
// ============================================================
// Simple markdown
// ============================================================

//================
// text wrapping (bold, etc)
// ===============
function wrapSelection(textarea, before, after) {
  const start = textarea.selectionStart;
  const end = textarea.selectionEnd;
  const selectedText = textarea.value.substring(start, end);
  const replacementText = before + selectedText + after;
  textarea.setRangeText(replacementText, start, end, "end");
  textarea.focus();
}
//================
// text prefixes (h1, etc)
// ===============
function prefixLine(textarea, prefix) {
  const value = textarea.value;
  const cursor = textarea.selectionStart;
  const lineStart = value.lastIndexOf("\n", cursor - 1) + 1;
  textarea.setRangeText(prefix, lineStart, lineStart, "end");
  textarea.focus();
}

// start markdown
const MarkdownToolbar = {
  buttons: [
    { label: "B", title: "Bold", type: "wrap", before: "**", after: "**" },
    { label: "I", title: "Italic", type: "wrap", before: "_", after: "_" },
    {
      label: "Code",
      title: "Inline code",
      type: "wrap",
      before: "`",
      after: "`",
    },
    { label: "H1", title: "Header 1", type: "line", before: "# " }, // TODO: add more — link, heading, etc.
  ],

  init(textareaSelector) {
    document.querySelectorAll(textareaSelector).forEach((textarea) => {
      const toolbar = document.createElement("div");
      toolbar.className = "markdown-toolbar";

      // loop over this.buttons, create a <button> for each,
      this.buttons.forEach(function (btnConfig) {
        // create button
        const btnEl = document.createElement("button");
        btnEl.type = "button";

        // give label and tooltip
        btnEl.textContent = btnConfig.label;
        btnEl.title = btnConfig.title;

        // add click listener
        btnEl.addEventListener("click", function (event) {
          event.preventDefault();
          if (btnConfig.type === "wrap") {
            wrapSelection(textarea, btnConfig.before, btnConfig.after);
          } else if (btnConfig.type === "line") {
            prefixLine(textarea, btnConfig.before);
          }
        });
        toolbar.appendChild(btnEl);
      });

      textarea.parentNode.insertBefore(toolbar, textarea);
    });
  },
};

// ============================================================
// Utilities
// ============================================================
function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

// ==============================================================
// plain JS document.ready
// ==============================================================
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOMContent loaded");
  // tables in dashboard
  const table = document.querySelector(".d-flex.flex-grow-1 table");
  if (table) {
    table.classList.add("table", "table-striped", "table-bordered");
  }
  document
    .querySelectorAll(".d-flex.flex-grow-1 th, .d-flex.flex-grow-1 td")
    .forEach(function (el) {
      el.classList.add("align-middle");
    });

  const widget = document.querySelector("#add-tag-widget");
  const checkboxgroup = document.querySelector(".checkbox_group");
  if (widget && checkboxgroup) {
    checkboxgroup.parentNode.insertBefore(widget, checkboxgroup);
  }
  //===========================================================
  // inits
  Toasts.initExisting();
  TagWidget.init();
  ArticleDelete.init();
  initPasswordLengthChecker();
  MarkdownToolbar.init(".article-text");

  // bind ajax forms
  AjaxForms.bind("#editUserModal form", {
    onSuccess: function (result) {
      if (result.name) {
        document.querySelector(".userNameDisplay").textContent = result.name;
        document.querySelector(
          `[data-user-id="${result.user_id}"]`,
        ).textContent = result.name;
      }
      if (result.email) {
        document.querySelector(".userEmailDisplay").textContent = result.email;
      }
      if (result.avatar_url) {
        const bustedUrl = result.avatar_url + "?t=" + Date.now();
        document.querySelector(".dashboard-pic").src = bustedUrl;
      }
    },
  });

  AjaxForms.bind("#editPasswordModal form", {
    onSuccess: function () {
      Toasts.show("message", "Password successfully changed.");
    },
  });

  const fillSpan = document.querySelector(".fill-ratings span");
  const starRatings = document.querySelector(".star-ratings");

  if (fillSpan && starRatings) {
    starRatings.style.width = fillSpan.offsetWidth + "px";
  }
});
