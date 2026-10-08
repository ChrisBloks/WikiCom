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
export const MarkdownToolbar = {
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
      toolbar.className = "markdown-toolbar btn-group w-100 mb-2";
      toolbar.setAttribute("role", "group")

      // loop over this.buttons, create a <button> for each,
      this.buttons.forEach(function (btnConfig) {
        // create button
        const btnEl = document.createElement("button");
        btnEl.type = "button";
        btnEl.className = "btn btn- ";

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