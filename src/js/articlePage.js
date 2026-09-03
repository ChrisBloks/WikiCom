

function saveRating(rating, article_id, rating_div) {
    // Send server request
    $.ajax({
        type: 'POST',
        url: "main.php",
        data: {
            func: 'saveRating',
            rating: rating,
            article_id: article_id,
        },
        success: function (response) {
            // Set the new rating
            rating_div.find("p.article_rating_display")
                .html("Current rating: ("+response['avg_rating']+")");
            console.log('saveRating Succesful');
        },
        error: function (){
            console.log('AJAX call failed!')
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

