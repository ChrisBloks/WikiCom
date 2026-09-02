const RATING_DIV = "div.rating_dropdown";
const RATING_BUTTON = "rating_dropdown_button";
const RATING_CONTENT = "div.rating_dropdown_content";
const RATING_OPTION = "rating_option";


// TODO: Add JSdoc
// Opens/Closes rating elements and saves rating
function handleRatingDropdowns(clickTarget) {
    // Get closest rating element
    let rating_div = clickTarget.closest(RATING_DIV);

    // Target is NOT inside a rating div: 
    if (rating_div.length == 0) {
        console.log('CLOSING RATINGS');
        // Make all dropdowns invisible
        $(RATING_CONTENT).css('display', 'none');
        $(RATING_DIV).attr('data-visible', 'false');
    }

    // Target IS inside a button
    else {
        let dropdown_content = rating_div.find(RATING_CONTENT);

        console.log(clickTarget);

        // If the BUTTON was clicked
        if (clickTarget.hasClass(RATING_BUTTON)) {
            console.log('CLICKED RATING BUTTON');
            // If content was hidden, make visible
            if (rating_div.attr('data-visible') == 'false') {
                dropdown_content.css('display', 'block');
                rating_div.attr('data-visible', 'true');
            }
            // if content was visible, make hidden
            else if (rating_div.attr('data-visible') == 'true') {
                dropdown_content.css('display', 'hidden');
                rating_div.attr('data-visible', 'false');
            }
            // Log error
            else {
                console.log('INVALID VALUE FOR RATING DIV data-visible')
            }
        }

        // If one of the dropdown options was clicked
        if (clickTarget.hasClass(RATING_OPTION)) {
            console.log('OPTION WAS CLICKED');
            try {
                let rating = clickTarget.attr('value');
                console.log(rating_div.find('input[name="article_id"]'));
                let article_id = rating_div.find('input[name="article_id"]').attr('data_article_id');
                console.log(rating, article_id);
                //saveRating(rating, article_id);
            }
            catch (error) {
                console.error('An error has occured:', error.message);
            }
        }
    }
}

function saveRating(rating, article_id) {
    // Send server request
    $.ajax({
        type: 'POST',
        url: "main.php",
        data: {
            action: 'saveRating',
            rating: rating,
            article_id: article_id,
        },
        success: function () {
            console.log('saveRating Succesful');
        }
    });
}



$(document).ready(function () {
    // On document start:
    console.log('articlePage.js READY');
    // Set all rating dropdowns to hidden
    $(RATING_DIV).attr('data-visible', 'false');

    // Set click event handler on document
    $(document).click(function (e) {
        console.log('DOCUMENT CLICK');
        // Open/Close/SaveRating
        handleRatingDropdowns($(e.target));
    })

    // Anything else?
});

