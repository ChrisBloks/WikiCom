import { ajaxPOST } from "./Ajax.js";

export const saveRating = {

    init() {
        // on submit
        document.querySelectorAll(".rating_button").forEach((button) => {
            button.addEventListener("click", async (event) => {
                console.log("Submit button clicked!");
                event.preventDefault();

                let rating_div = event.currentTarget.parentNode;
                let article_id = rating_div.querySelector('input[name="article_id"]').value;
                let user_rating = rating_div.querySelector("select.rating_select").value;

                await ajaxPOST(
                    "main.php",
                    "json",
                    { action: "saveRating", rating: user_rating, article_id: article_id },
                    (response) => {
                        console.log('response: ', response);
                        // set new rating
                        console.log(rating_div.querySelector("div.star-ratings"));
                        let percent = response['avg_rating'] / 5 * 100
                        rating_div.querySelector("div.star-ratings")
                            .innerHTML('<div class="fill-ratings" style="width: ' + percent + '%;">'
                                + '<span>★★★★★</span></div><div class="empty-ratings">'
                                + '<span>★★★★★</span></div><div class="count-rating">'
                                + '(' + response['n_ratings'] + ')</div>');

                        console.log('saveRating Succesful');
                    },
                    (error) => {
                        console.log('AJAX call failed!');
                        console.log(error);
                    }
                )
            })
        }
        )
    }
}
