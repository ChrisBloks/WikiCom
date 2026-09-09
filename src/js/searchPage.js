

/**
 * 
 * @param {jQuery} checkbox_group 
 * @param {string} search_input 
 */
function updateCheckboxGroup(checkbox_group, search_input){

    // Get all checkboxes
    checkboxes = checkbox_group.find('input[type="checkbox"]');

}

$(document).ready(function() {
    console.log('searchPage.js READY');

    console.log($('.searchField'));

    // Searchbar update
    $('.searchField').on({
        input: function(e) {
            $searchfield = $(this);
            $checkboxgroup = 
            console.log($(this));
            console.log('INPUT CHANGE');

            
        }
    })
})