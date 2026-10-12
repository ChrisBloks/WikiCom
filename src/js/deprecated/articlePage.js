

function saveRating(rating, article_id, rating_div) {
    // Send server request
    $.ajax({
        type: 'POST',
        url: "main.php",
        data: {
            action: 'saveRating',
            rating: rating,
            article_id: article_id,
        },
        success: function (response) {
            console.log('response: ', response);
            // Set the new rating
            console.log(rating_div.find("div.star-ratings"));
            
            let percent = response['avg_rating']/5 *100

            rating_div.find("div.star-ratings")
                .html('<div class="fill-ratings" style="width: '+percent+'%;">'
                    +'<span>★★★★★</span></div><div class="empty-ratings">'
                    +'<span>★★★★★</span></div><div class="count-rating">'
                    +'('+response['n_ratings']+')</div>');


                

            console.log('saveRating Succesful');
        },
        error: function (error)
        {
            console.log('AJAX call failed!');
            console.log(error.responseText);
        }
    });
}

$(document).ready(function () {
    // On document start:
    console.log('articlePage.js READY');

    // Submit rating button
    $('input[class="rating_button"]').on({
        click: function(e){
            console.log("Submit button clicked!");
            
            let rating_div = $(this).parent();
            let article_id = rating_div.find('input[name="article_id"]').val();
            let user_rating = rating_div.find("select.rating_select").val();

            saveRating(user_rating, article_id, rating_div);
        }
    })

    // Anything else?
});

