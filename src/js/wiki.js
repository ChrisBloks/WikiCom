// ============================================================
// Bootstrap Toasts — 
// ============================================================
const Toasts = {
  show(type, text) {
    // variables needed to make a toast
    const bgMap = {
      error: "text-bg-danger",
      message: "text-bg-success",
    };
    const bgClass = bgMap[type] || "text-bg-secondary";
    const label = type.charAt(0).toUpperCase() + type.slice(1);

    // create a Toast div inside the empty .toast_container
    const $toast = $('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">')
      .addClass(bgClass)
      .append(
        $('<div class="toast-header">').append(
          $('<strong class="me-auto">').text(label),
          $('<small class="toast-timestamp">').text('just now'),
          $('<button type="button" class="btn-close toast-button" data-bs-dismiss="toast" aria-label="Close">')
        ),
        $('<div class="toast-body">').text(text)
      );

    $('#toast-container').append($toast);
    this._activate($toast);
  },

  // initialize toasts
  initExisting() {
    $('.toast').each((_, el) => this._activate($(el)));
  },

  // private function - initializes the toast and auto-hides/removes itself
  _activate($toast) {
    const toastElement = new bootstrap.Toast($toast[0], { autohide: true, delay: 5000 });
    $toast.on('hidden.bs.toast', function () {
      $(this).remove();
    });
    toastElement.show();
  },
};


// ==========================================================================
// AjaxForms — generic AJAX submit handling for modal forms (bootstrap-modal)
// ==========================================================================
const AjaxForms = {
  // toggle the spinning <span> element inside the form
  setLoading($el, isLoading) {
    const $spinner = $el.find('.spinner-border');
    $spinner.toggleClass('d-none', !isLoading);
    $el.prop('disabled', isLoading);
  },

  // bind the form
  bind(formSelector, options) {
    options = options || {};
    const $form = $(formSelector);
    if (!$form.length) return;

    const $modal = $form.closest(".modal");
    const $errorBox = $modal.find('[id$="-errors"]');

    // form handling starts here
    $form.on("submit", (e) => {
      e.preventDefault();

      // collect vars needed and set button to disabled
      const $submitBtn = $form.find('[type="submit"]');
      this.setLoading($submitBtn, true);
      $errorBox.addClass("d-none");

      // ajax-call
      $.ajax({
        url: "main.php",
        method: "POST",
        data: new FormData($form[0]),
        processData: false,
        contentType: false,
        dataType: "json",
        success: (result) => {
          if (result.success) {
            Toasts.show('message', result.message);
            if (typeof options.onSuccess === 'function') options.onSuccess(result);
            bootstrap.Modal.getInstance($modal[0])?.hide();
          } else if ($errorBox.length) {
            const messages = result.errors && result.errors.length ? result.errors : [result.message];
            $errorBox.html(messages.map((msg) => `<div>${msg}</div>`).join(""));
            $errorBox.removeClass("d-none");
          } else {
            Toasts.show('error', result.message || 'Something went wrong.');
          }
        },
        error: () => {
          Toasts.show('error', 'Network error, please try again.');
        },
        complete: () => {
          this.setLoading($submitBtn, false);
        },
      });
    });
  },
};


// ============================================================
// TagWidget — 
// ============================================================
const TagWidget = {
  init() {
    $("#new-tag-name").on("keydown", (event) => {
      if (event.key === "Enter") {
        event.preventDefault();
        $("#add-tag-btn").trigger("click");
      }
    });

    $("#add-tag-btn").on("click", () => this._addTag());
  },

  // -priavte func, adds the tag to the div
  _addTag() {
    const tagName = $("#new-tag-name").val().trim();
    if (!tagName) return;

    let alreadyAdded = false;
    $(".checkbox_group label").each(function () {
      if ($(this).text().trim().toLowerCase() === tagName.toLowerCase()) {
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

    $(".checkbox_group").append(checkboxHtml);
    $("#new-tag-name").val("").trigger("focus");
  },
};


// ============================================================
// ArticleDelete — AJAX delete for article rows
// ============================================================
const ArticleDelete = {
  init() {
    $(document).on("submit", ".ajax-delete-form", (event) => {
      event.preventDefault();

      const $form = $(event.currentTarget);
      const articleId = $form.find('input[name="id"]').val();

      if (!confirm("Are you sure you want to delete this article?")) {
        return;
      }

      // start ajax call
      $.ajax({
        url: "main.php",
        method: "POST",
        data: { action: "deleteArticle", id: articleId },
        success: function (response) {
          if (response.success) {
            $form.closest("tr").fadeOut(200, function () {
              $(this).remove();
            });
          } else {
            alert(response.message || "Could not delete the article.");
          }
        },
      });
    });
  },
};


// ============================================================
// PasswordLengthChecker — min-length validation
// ============================================================
function initPasswordLengthChecker() {
  const minLength = parseInt($("#newpassword-1").data("min-length"), 10) || 4;

  function showError($input, message) {
    $input.next(".feedback").remove();
    if (message) {
      $input.after(`<div class="feedback error text-danger">${message}</div>`);
    }
  }

  function validatePasswordLength() {
    const newPassword = $("#newpassword-1").val();
    if (newPassword.length > 0 && newPassword.length < minLength) {
      showError($("#newpassword-1"), `Password must be at least ${minLength} characters.`);
      return false;
    }
    showError($("#newpassword-1"), "");
    return true;
  }

  $("#newpassword-1, #newpassword-2").on("input", validatePasswordLength);

  // call function on submit password
  $(".edit-password").on("submit", (e) => {
    if (!validatePasswordLength()) e.preventDefault();
  });
}


// ============================================================
// Utilities
// ============================================================
function escapeHtml(str) {
  return $("<div>").text(str).html();
}

// ============================================================
// $(document).ready STARTS HERE
// ============================================================
// call functions and utilities needed on document page
// ============================================================

// bs markdown editor for 
$(document).ready(function () {
  $(".article-text").bsMarkdownEditor({
    minHeight: 240,
    preview: true,
    mode: "editor",
    resize: "vertical",
    size: "sm",
    btnClass: "border-0",
    wrapperClass: null,
    actions: "all",
    lang: "en",
  });

  $(".d-flex.flex-grow-1 table").addClass("table table-striped table-bordered");
  $(".d-flex.flex-grow-1 th, .d-flex.flex-grow-1 td").addClass("align-middle");
  $("#add-tag-widget").insertBefore(".checkbox_group");

  // initialize functions
  // @marius we should start splitting js files into classes or something
  Toasts.initExisting();
  TagWidget.init();
  ArticleDelete.init();
  initPasswordLengthChecker();

  // bind values to form
  AjaxForms.bind("#editUserModal form", {
    onSuccess: function (result) {
      if (result.name) {
        $(".userNameDisplay").text(result.name);
        $(`[data-user-id="${result.user_id}"]`).text(result.name);
      }
      if (result.email) {
        $(".userEmailDisplay").text(result.email);
      }
      if (result.avatar_url) {
        const bustedUrl = result.avatar_url + "?t=" + Date.now();
        $(".dashboard-pic, #avatar-preview").attr("src", bustedUrl);
      }
    },
  });

  AjaxForms.bind("#editPasswordModal form", {
    onSuccess: function () {
      Toasts.show("message", "Password successfully changed.");
    },
  });

  var star_rating_width = $(".fill-ratings span").width();
  $(".star-ratings").width(star_rating_width);
});