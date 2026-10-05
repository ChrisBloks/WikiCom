/**
 *  Loads js functions on document.ready
 * 2 options for the future:
 *  1. Split the main.js into different files like article.js, dashboard.js and import them on page load
 *  2. Create a switch statement in the main that loads the components based on
 *    document.body.dataset.page
 * 
 * - Marius
 * 
 * OR 
 *  shown in bootstrap.js
 * 
 * OR
 *  minify js
 */



// ==============================================================
// plain JS document.ready
// ==============================================================

// imports
import { AjaxForms } from "./services/AjaxForms.js";
import { ArticleDelete } from "./components/ArticleDelete.js"
import { TagWidget } from "./components/Tagwidget.js";
import { initPasswordLengthChecker } from "./components/PasswordLengthChecker.js";
import { Toasts } from "./components/Toasts.js";
import { MarkdownToolbar } from "./components/MarkdownEditor.js";
import { saveRating } from "./services/SaveRating.js";
import { updateCheckboxGroup, resetCheckboxesToDefault, } from "./components/checkboxes.js";

// document.ready
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

    // editArticle add tag widget
  const widget = document.querySelector("#add-tag-widget");
  const checkboxgroup = document.querySelector(".checkbox_group");
  if (widget && checkboxgroup) {
    checkboxgroup.parentNode.insertBefore(widget, checkboxgroup);
  }

  //===========================================================
  // inits
  //===========================================================
  Toasts.initExisting();
  TagWidget.init();
  ArticleDelete.init();
  initPasswordLengthChecker();
  MarkdownToolbar.init(".article-text");
  saveRating.init();

  // bind ajax forms
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

  // searchbar update (js)
  document.querySelectorAll(".searchField").forEach(function (search_field) {
    search_field.addEventListener("input", function (_) {
      console.log("input change");
      let checkbox_group = this.parentNode.querySelector(".checkbox_group");
      updateCheckboxGroup(checkbox_group, this.value, 1);
    });
  });

  // search form ajax
    AjaxForms.bind("#search_form", {
    onSuccess: function (result) {
      result.articles_info.forEach(function (article) {
        // Process each article
      });
    },
  });

});
});