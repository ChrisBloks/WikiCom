// ============================================================
// SearchForm: AJAX search form handling
// ============================================================
import { AjaxForms } from "../services/AjaxForms.js";
import { ajaxGET } from "../services/Ajax.js";
import { ResponseHandler } from "../ResponseHandler.js";


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
                                cell.innerHTML = `<a class="cell-link" data-targetpage =pageArticle data-id=${article.id}">${article[row_id] || ""}</a>`;
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
                    // ============================================================
                    // Cell link in table event
                    // ============================================================
                    let links = document.getElementsByClassName("cell-link");

                    for (let link of links) {
                        link.addEventListener("click", async function (_) {
                            console.log("click");
                            let articleID = link.dataset.id;
                            let page = link.dataset.targetpage;
                            await ajaxGET(
                                "main.php?reqType=ajax&action=" + page + "&articleID=" + articleID,
                                "json",
                                (response) => {
                                    if (!Array.isArray(response)) return;
                                    console.log("response reached")
                                    ResponseHandler.handleResponse(response);
                                },
                                (error) => console.error("Content unable to be loaded:", error),
                            );
                        }
                        )
                    }
                });
            },
        });
    }
}