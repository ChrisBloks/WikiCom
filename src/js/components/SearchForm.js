// ============================================================
// SearchForm: AJAX search form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";


export const SearchForm = {
    init() {
        // search form ajax
        AjaxForms.bind("#search_form", {
            onSuccess: function (result) {
                let search_table = document.getElementById("search_table");
                let tbody = search_table.querySelector("tbody");
                tbody.replaceChildren();
                let rows = search_table.tHead.rows[0].cells;
                let ids = Array.from(rows, (x) => x.id);
                result.articles_info.forEach(function (article) {
                    let row = tbody.insertRow(0);
                    ids.forEach(function (row_id) {
                        let cell = row.insertCell();
                        switch (row_id) {
                            case "title":
                                cell.innerHTML = `<a href="?page=article&id=${article.id}">${article[row_id] || ""}</a>`;
                                break;
                            case "rating":
                                cell.innerHTML = `<div class="star-ratings">
                                <div class="fill-ratings" style="width: ${article[row_id] * 20}%">
                                <span>★★★★★</span>
                                </div>
                                <div class="empty-ratings">
                                <span>★★★★★</span>
                                </div>
                                <div class="count-rating">
                                (${article['Nratings'] || 0})
                                </div>
                                </div>`;
                                const fillSpan = document.querySelector(".fill-ratings span");
                                const starRatings = document.querySelector(".star-ratings");

                                if (fillSpan && starRatings) {
                                    starRatings.style.width = fillSpan.offsetWidth + "px";
                                }
                                break;
                            default:
                                cell.innerHTML = article[row_id] || "";
                        }
                    });
                });
            },
        });
    }
}