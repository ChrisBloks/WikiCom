// ============================================================
// Binds link in table 
// ============================================================
import { ajaxGET } from "../services/Ajax.js";
import { ResponseHandler } from "../ResponseHandler.js";
import { HistoryTracking } from "../services/HistoryTracking.js";

export function bindCellLink() {
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
                    console.log("response reached");
                    ResponseHandler.handleResponse(response);
                    console.log('bindCellLink {page}: ' + page);
                    HistoryTracking.add(page, [parseInt(articleID)]);
                },
                (error) => console.error("Content unable to be loaded:", error),
            );
        }
        )
    }
}