$(document).ready(function () {
  // load bsmarkdown before anything else on page or it doesnt load
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

  // UX password length
  $(function () {
    initPasswordLengthChecker();
  });


  //============================================================================
  // Edit article
  //===========================================================================

// Bootstrapping page
  $(".d-flex.flex-grow-1 table").addClass("table table-striped table-bordered");
  $(".d-flex.flex-grow-1  th, .d-flex.flex-grow-1  td").addClass(
    "align-middle",
  );
  $("#add-tag-widget").insertBefore(".checkbox_group");
 
  // Add new tag
  $("#new-tag-name").on("keydown", function (event) {
    // Enter as "Add tag"
    if (event.key === "Enter") {
      event.preventDefault();
      $("#add-tag-btn").trigger("click");
    }
  });


  $("#add-tag-btn").on("click", function () {
    var tagName = $("#new-tag-name").val().trim();
    if (!tagName) return;

    var alreadyAdded = false;
    $(".checkbox_group label").each(function () {
      if ($(this).text().trim().toLowerCase() === tagName.toLowerCase()) {
        alreadyAdded = true;
      }
    });
    if (alreadyAdded) {
      alert("That tag is already in the list.");
      return;
    }

    var safeId = tagName.toLowerCase().replace(/[^a-z0-9]+/g, "-");

    var checkboxHtml =
      '<input type="checkbox" name="existing_tag[' +
      safeId +
      ']" id="new-tag-' +
      safeId +
      '" class="Existing-tag form-check-input" value="0" checked>' +
      '<label for="new-tag-' +
      safeId +
      '">' +
      escapeHtml(tagName) +
      "</label><br>";

    $(".checkbox_group").append(checkboxHtml);
    $("#new-tag-name").val("").trigger("focus");
  });

  //============================================================================
  // Delete button
  // ===========================================================================
  $(document).on("submit", ".ajax-delete-form", function (event) {
    event.preventDefault();

    var $form = $(this);
    var articleId = $form.find('input[name="id"]').val();

    if (!confirm("Are you sure you want to delete this article?")) {
      return;
    }

    console.log("Button pressed");

    $.ajax({
      url: "main.php",
      method: "POST",
      data: {
        action: "deleteArticle",
        id: articleId,
      },
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
});
//============================================================================
// Star rating width
// ===========================================================================

$(document).ready(function () {
  var star_rating_width = $('.fill-ratings span').width();
  $('.star-ratings').width(star_rating_width);
});

// =======================================================
// Functions
// ========================================================
// basic HTML-escaping so a tag name can't break out of the attribute/markup
function escapeHtml(str) {
  return $("<div>").text(str).html();
}

//==============================================================
// JS function to set a minimum length password 
// + showing the error on page
function initPasswordLengthChecker() {
  // set minimum length of needed password to the min-length attribute (or 4)
  const minLength = parseInt($('#newpassword-1').data('min-length'), 10) || 4;

  // creates a div for showing the error message
  function showError($input, message) {
    $input.next('.feedback').remove();
    if (message) {
      $input.after(`<div class="feedback error text-danger">${message}</div>`);
    }
  }

  // validates the length of a password
  function validatePasswordLength() {
    const newPassword = $('#newpassword-1').val();
    if (newPassword.length > 0 && newPassword.length < minLength) {
      showError($('#newpassword-1'), `Password must be at least ${minLength} characters.`);
      return false;
    }
    showError($('#newpassword-1'), '');
    return true;
  }

  $('#newpassword-1, #newpassword-2').on('input', function () {
    validatePasswordLength();
  });

  $('.edit-password').on('submit', function (e) {
    const lengthOk = validatePasswordLength();

    if (!lengthOk) {
      e.preventDefault();
    }
  });
}

