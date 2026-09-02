$(document).ready(function () {
  $(".delete-button").on("click", function () {
    alert("Are you sure you want to delete this article?");
  });

  $("#new-tag-name").on("keydown", function (event) {
    if (event.key === "Enter") {
      event.preventDefault();
      $("#add-tag-btn").trigger("click"); // optional: treat Enter as "Add tag"
    }
  });

  $("#add-tag-widget").insertBefore(".checkbox_group");

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
      '<input type="checkbox" name="Existing_tag[' +
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
});

// =======================================================
// Functions
// ========================================================
// basic HTML-escaping so a tag name can't break out of the attribute/markup
function escapeHtml(str) {
  return $("<div>").text(str).html();
}
