// TODO: group A — run ONCE per real page load
//       Router, QuickActions
//       Running these again after a swap would stack duplicate listeners.

// TODO: group B — wrap in a function initPage() — run on load AND after every swap
//       binds, MarkdownToolbar, TagWidget, rating buttons, 
//       search fields, Toasts.initExisting, ...


// ==============================================================
// plain JS document.ready
// ==============================================================

// imports
import { Router } from "./services/Router.js";
import { AjaxForms } from "./services/AjaxForms.js";
import { ArticleDelete } from "./components/ArticleDelete.js"
import { TagWidget } from "./components/Tagwidget.js";
import { initPasswordLengthChecker } from "./components/PasswordLengthChecker.js";
import { Toasts } from "./components/Toasts.js";
import { MarkdownToolbar } from "./components/MarkdownEditor.js";
import { SearchForm } from "./components/SearchForm.js";
import { ContactForm } from "./components/ContactForm.js";
import { RegisterUser } from "./components/RegisterUser.js";
import { LoginForm } from "./components/LoginForm.js";
import { EditArticleForm } from "./components/EditArticle.js";

// document.ready
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOMContent loaded");
  // ALWAYS INIT
  Router.init();

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



  //===========================================================
  // inits
  //===========================================================
  Toasts.initExisting();
  TagWidget.init();
  ArticleDelete.init();
  initPasswordLengthChecker();
  MarkdownToolbar.init(".article-text");
  SearchForm.init();
  ContactForm.init();
  RegisterUser.init();
  LoginForm.init();
  EditArticleForm.init();

  // document.querySelectorAll(".checkbox_group").forEach(function (checkbox_group) {
  //   resetCheckboxesToDefault(checkbox_group);
  // });

  // // searchbar update (js)
  // document.querySelectorAll(".searchField").forEach(function (search_field) {
  //   search_field.addEventListener("input", function (_) {
  //     console.log("input change");
  //     let checkbox_group = this.parentNode.querySelector(".checkbox_group");
  //     updateCheckboxGroup(checkbox_group, this.value, 1);
  //   });
  // });

});