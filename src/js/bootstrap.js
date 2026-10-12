// 
//  WIP
//


//example of how only loading components when needed could work maybe?

// lets say I need only: toasts, article_delete, and ajax-forms (dashboard):
// php side:
<body data-components="toasts,article-delete,ajax-forms"></body>


// javascript side:
const COMPONENTS = {
  "toasts": () => import("./views/Toasts.js"),
  "article-delete": () => import("./tools/ArticleDelete.js"),
  "ajax-forms": () => import("./tools/AjaxForms.js"),
  "rating-widget": () => import("./tools/RatingWidget.js"),
  "markdown-toolbar": () => import("./views/MarkdownEditor.js"),
};

// (document).ready
// you can fetch comonents through document.body.dataset.components, split by ,-delimiter
document.addEventListener("DOMContentLoaded", async () => {
  const names = (document.body.dataset.components || "").split(",").filter(Boolean);

  for (const name of names) {
    const module = COMPONENTS[name];
    if (!module) {
      console.warn(`Unknown component requested: "${name}"`);
      continue;
    }
    const exported = await module();

    // export the js-object and if it has an .init() immediately run that too
    Object.values(exported)[0]?.init?.();
  }
});