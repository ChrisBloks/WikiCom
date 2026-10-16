// ============================================================
// SearchForm: AJAX search form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";
import { insertArticleInfoToTable } from "../utils/insertArticleInfoToTable.js";
import { bindCellLink } from "../utils/bindCellLink.js";


export const SearchForm = {
    init() {
        // search form ajax
        AjaxForms.bind("#search_form", {
            onSuccess: function (result) {
                // ============================================================
                // Insert article info into table
                // ============================================================
                insertArticleInfoToTable('search_table', result);
                // ============================================================
                // Cell link in table event
                // ============================================================
                bindCellLink();
            },
        });
    }
}