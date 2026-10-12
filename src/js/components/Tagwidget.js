// ============================================================
// TagWidget —
// ============================================================
import { escapeHtml } from "../utils/escapeHtml.js";

export const TagWidget = {
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
      `<input type="checkbox" name="articletags[${safeId}]" id="new-tag-${safeId}" class="Existing-tag form-check-input" value="0" checked>` +
      `<label for="new-tag-${safeId}">${escapeHtml(tagName)}</label><br>`;

    document
      .querySelector(".checkbox_group")
      .insertAdjacentHTML("beforeend", checkboxHtml);
    const input = document.getElementById("new-tag-name");
    input.value = "";
    input.focus();
  },
};