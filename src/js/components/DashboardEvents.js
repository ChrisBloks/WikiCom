// ============================================================
// DashboardEvents: Binds all events needed in dashboard
// ============================================================
import { ajaxPOST } from "../services/Ajax.js";
import { AjaxForms } from "../services/AjaxForms.js";
import { ajaxGET } from "../services/Ajax.js";
import { ResponseHandler } from "../ResponseHandler.js";


export const DashboardEvents = {
    init() {
        // ============================================================
        // Article delete button event
        // ============================================================
        document.querySelectorAll('.ajax-delete-button').forEach((button) => {


            // on submit
            button.addEventListener("click", async (event) => {
                // if it doesnt have the delete form tag -> return
                // if (!event.target.matches(".ajax-delete-form")) return;
                // event.preventDefault();
                console.log(button.dataset.articleId);
                // get vars
                let articleId = button.dataset.articleId


                // Popup for user confimration
                if (!confirm("Are you sure you want to delete this article?")) return;

                // start ASYNC
                await ajaxPOST(
                    "main.php?reqType=ajax",
                    "json",
                    { action: "deleteArticle", id: articleId },
                    (response) => {
                        if (!response.success) {
                            // if response.success is false or not set, return error
                            alert(response.message || "Could not delete the article.");
                            return;
                        }

                        // else -> animation to remove row from the table
                        console.log("success!");
                        const row = button.closest("tr");
                        if (!row) return;
                        row.style.transition = "opacity 0.2s";
                        row.style.opacity = "0";
                        setTimeout(() => row.remove(), 200);
                    },
                    (error) => {
                        // if error -> log console
                        console.error(error);
                        alert("Request failed, please try again.");
                    },
                );
            });
        });

        // ============================================================
        // New article button event
        // ============================================================
        let button = document.getElementById("create_new_article_button")
        button.addEventListener("click", async function (_) {
            let articleID = 0;
            let page = 'pageEditArticle'
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

        // ============================================================
        // Cell link in table event
        // ============================================================
        let links = document.getElementsByClassName("cell-link");

        for (let link of links) {
            link.addEventListener("click", async function (_) {
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

        // ============================================================
        // Edit user info model event
        // ============================================================
        AjaxForms.bind("#editUserModal form", {
            onSuccess: function (result) {
                if (result.name) {
                    document.querySelector(".userNameDisplay").textContent = result.name;
                    document.querySelector(
                        `[data-user-id="${result.user_id}"]`,
                    ).textContent = result.name;
                }
                if (result.email) {
                    document.querySelector(".userEmailDisplay").textContent = result.email;
                }
                if (result.avatar_url) {
                    const bustedUrl = result.avatar_url + "?t=" + Date.now();
                    document.querySelector(".dashboard-pic").src = bustedUrl;
                }
            },
        });

        // ============================================================
        // Edit user password model event
        // ============================================================

        AjaxForms.bind("#editPasswordModal form", {
            onSuccess: function () {
                console.log("Password changed successfully");
            },
        });
    }
}