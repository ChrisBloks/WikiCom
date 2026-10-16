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
import { TagWidget } from "./components/Tagwidget.js";
import { initPasswordLengthChecker } from "./components/PasswordLengthChecker.js";
import { Toasts } from "./components/Toasts.js";
import { MarkdownToolbar } from "./components/MarkdownEditor.js";
import { bindEvents } from "./utils/bindEvents.js";
import { HistoryNavigation } from "./services/HistoryNavigation.js";

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
  initPasswordLengthChecker();
  MarkdownToolbar.init(".article-text");


  let url = window.location.href;

  const match = url.match(/[?&]page=([^&#]*)/);
  const page = match ? match[1] : 'home';
  bindEvents(page);

  HistoryNavigation.init();

});