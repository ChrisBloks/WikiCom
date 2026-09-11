


function updateCheckboxGroup(checkbox_group, search_input, max_distance = 2) {
    search_input = search_input.toLowerCase();

    // Get all checkboxes


    // If search_input is empty, show everything
    if (search_input.length == 0) {
        resetCheckboxesToDefault(checkbox_group);
        return;
    }
    orderCheckboxes(checkbox_group, search_input, max_distance);
}


function resetCheckboxesToDefault(checkbox_group) {
    let checkbox_divs = checkbox_group.find('div.checkbox_container');


    // Make each checkbox visible and store its index for sorting
    let checkbox_array = []
    checkbox_divs.each(function (index) {
        let checkbox_div = $(this);
        checkbox_div.css('display', 'block'); // make visibble

        // Store the jQuery object and the tag label 'PHP, code, tag1, etc...'
        checkbox_array[index] = [checkbox_div, checkbox_div.find('label').html()];
    });

    // Find alphabetical ordering based on label
    checkbox_array.sort(function (a, b) {
        return a[1].localeCompare(b[1]);
    });


    // Build up html to put on page
    html = "";
    checkbox_array.forEach(checkbox_div => {
        html += checkbox_div[0].get(0).outerHTML;
    });

    // update page
    checkbox_group.html(html);
}

function orderCheckboxes(checkbox_group, search_input, max_distance) {
    let checkbox_divs = checkbox_group.find('div.checkbox_container'); // jQuery set

    // Collect checkboxes for ordering 
    let checkbox_array = [];

    // For each checkbox element, calculate its string distance to the search input
    checkbox_divs.each(function (index) {
        let div = $(this)
        let label = div.find('label');
        let string_distance = levenshtein_distance(
            label.text().toLowerCase(),
            search_input);

        // Hide elements if their string distance exceeds the max distance
        if (string_distance <= max_distance) {
            $(this).css('display', 'block');
        }
        else {
            $(this).css('display', 'none');
        }

        //              [int => [jQuery, int, string]]
        checkbox_array[index] = [div, string_distance, label.text().toLowerCase()]
    });

    // Attempt to sort by distance, if distance is equal sort alphabetically
    checkbox_array.sort(function (a, b) {
        let dist_a = a[1];
        let dist_b = b[1];
        // sort by distance
        if (dist_a > dist_b) {
            return 1;
        }
        if (dist_a < dist_b) {
            return -1;
        }
        // dist_a == dist_b
        // sort alphabetically
        else {
            let label_a = a[2];
            let label_b = b[2];
            return label_a.localeCompare(label_b);
        }
    });

    // Build up html to put on page
    html = "";
    checkbox_array.forEach(checkbox_div => {
        html += checkbox_div[0].get(0).outerHTML;
    });

    // update page
    checkbox_group.html(html);
}



/**
 * Calculate the edit-distance between two strings.
 * NOTE: not bi-directional, dis(a,b) =/= dis(b,a)
 * See also:
 * https://en.wikipedia.org/wiki/Wagner–Fischer_algorithm,
 * https://www.30secondsofcode.org/js/s/levenshtein-distance/
 * 
 * @param {string} str1 label text
 * @param {string} str2 user search input
 * @returns 
 */
function levenshtein_distance(str1, str2) {
    if (str1.length == 0) return str2.length;
    if (str2.length == 0) return str1.length;

    // Create 2d array 
    var arr = Array(str2.length + 1).fill(0).map(_ => Array(str1.length + 1));

    // Populate first row and first column
    for (let i = 0; i <= str1.length; i++) {
        arr[0][i] = i;
    }
    for (let j = 0; j <= str2.length; j++) {
        arr[j][0] = j;
    }

    let chr1;
    let chr2;
    let cost = 0;
    // Loop over both strings, calculate pair-wise substitution cost by character
    for (let i = 1; i <= str1.length; i++) {
        chr1 = str1[i - 1];
        for (let j = 1; j <= str2.length; j++) {
            // Calculate substitution cost
            chr2 = str2[j - 1];
            (chr1 == chr2 ? cost = 0 : cost = 1)

            arr[j][i] = Math.min(
                arr[j - 1][i - 1] + cost, // Substitution
                arr[j][i - 1], // Insertion is free
                arr[j - 1][i] + 1 // Deletion
            );
        }
    }
    return arr[str2.length][str1.length];
}

$(document).ready(function () {
    console.log('searchPage.js READY');
    //TODO: should be given sorted by default
    $(".checkbox_group").each(function (_) {
        resetCheckboxesToDefault($(this));
    });

    // Searchbar update
    $('.searchField').on({
        input: function (e) {
            console.log('INPUT CHANGE')
            let search_field = $(this);
            let checkbox_group = search_field.parent()
                .find(".checkbox_group");
            updateCheckboxGroup(checkbox_group, search_field.val(), 2);

        }
    })
})

//https://stackoverflow.com/questions/7389069/how-can-i-make-console-log-show-the-current-state-of-an-object
function log(obj) {
    console.log(JSON.parse(JSON.stringify(obj)));
}