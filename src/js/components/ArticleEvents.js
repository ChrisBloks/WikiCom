// ============================================================
// Events on article page
// ============================================================
import { ajaxPOST } from "../services/Ajax.js";
import { AjaxForms } from "../services/AjaxForms.js";
import { ajaxGET } from "../services/Ajax.js";
import { ResponseHandler } from "../ResponseHandler.js";
import { bindCellLink } from "../utils/bindCellLink.js";
import { insertArticleInfoToTable } from "../utils/insertArticleInfoToTable.js";

export const ArticleEvents = {
    init() {
        // ============================================================
        // Article tag button event
        // ============================================================
        let buttons = document.querySelectorAll('.tag_button_article');
        buttons.forEach(function (button) {
            button.addEventListener("click", async function (e) {
                e.preventDefault();
                await ajaxGET(
                    "main.php?reqType=ajax&action=pageSearch",
                    "json",
                    async function (response) {
                        if (!Array.isArray(response)) return;
                        console.log("response reached");
                        ResponseHandler.handleResponse(response);

                        let form = document.querySelector('#search_form');
                        let formdata_var = new FormData(form);
                        formdata_var.append('Tag[' + button.dataset.tagName + ']', button.dataset.tagId)

                        await ajaxPOST(
                            "main.php?reqType=ajax",
                            "json",
                            formdata_var,
                            (result) => {
                                if (result.success) {
                                    let checkboxes = document.getElementsByClassName('tag_checkbox');
                                    let checkbox = Array.from(checkboxes).filter(el => el.value === button.dataset.tagId);
                                    checkbox[0].checked = true;
                                    // ============================================================
                                    // Insert article info into table
                                    // ============================================================
                                    insertArticleInfoToTable('search_table', result);
                                    // ============================================================
                                    // Cell link in table event
                                    // ============================================================
                                    bindCellLink();
                                } else {
                                    if (result.errors?.length == 0) {
                                        Toasts.show("error", "Something went wrong.");
                                    } else {
                                        result.errors?.forEach((msg) => Toasts.show("error", msg));
                                    }
                                }
                            },
                            (error) => {
                                console.error(error);
                                Toasts.show("error", "Request failed, please try again.");
                            },
                        );

                    },
                    (error) => console.error("Content unable to be loaded:", error),
                );
            })
        })
    }
}