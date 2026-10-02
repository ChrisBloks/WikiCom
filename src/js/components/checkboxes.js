import { levenshtein_distance } from "../utils/levenshtein_distance.js";

/**
 * Reorders checkbox elements in the checkboxgroup based on the checkbox' label's edit distance from search_input.
 * <p>
 * Checkboxes whose label has an edit distance higher than max_distance are hidden.
 *
 * @param {Element} checkbox_group
 * @param {string} search_input
 * @param {int} max_distance
 * @returns {void} void
 */
export function updateCheckboxGroup(checkbox_group, search_input, max_distance = 2) {
  search_input = search_input.toLowerCase();

  // Get all checkboxes

  // If search_input is empty, show everything
  if (search_input.length == 0) {
    resetCheckboxesToDefault(checkbox_group);
    return;
  }
  orderCheckboxes(checkbox_group, search_input, max_distance);
}

/**
 *resets checkboxes to default state with no filtering
 * @param {*} checkbox_group
 */
export function resetCheckboxesToDefault(checkbox_group) {
  let checkbox_divs = checkbox_group.querySelectorAll("div.checkbox_container");

  // Make each checkbox visible and store its index for sorting
  let checkbox_array = [];

  checkbox_divs.forEach(function (checkbox_div, index) {

    // are these two the same?
    checkbox_div.style.display = "block";

    checkbox_array[index] = [checkbox_div, checkbox_div.querySelector("label").innerHTML];
  });

  // Find alphabetical ordering based on label
  checkbox_array.sort(function (a, b) {
    return a[1].localeCompare(b[1]);
  });

  // Build up elements to put on page
  checkbox_group.replaceChildren();
  checkbox_array.forEach((checkbox_div) => {
    checkbox_group.append(checkbox_div[0]);
  });
}

/**
 *
 * @param {*} checkbox_group
 * @param {*} search_input
 * @param {*} max_distance
 */
export function orderCheckboxes(checkbox_group, search_input, max_distance) {
  let checkbox_divs = checkbox_group.querySelectorAll("div.checkbox_container"); // jQuery set

  // Collect checkboxes for ordering
  let checkbox_array = [];

  // For each checkbox element, calculate its string distance to the search input
  checkbox_divs.forEach(function (div,index) {
    let label = div.querySelector("label");
    let label_text = label.textContent.toLowerCase()


    let string_distance = levenshtein_distance(
      label_text,
      search_input,
    );
    // Hide elements if their string distance exceeds the max distance
    if (string_distance <= max_distance) {
      div.style.display = "block";
    } else {
      div.style.display = "none";
    }

    //              [int => [jQuery, int, string]]
    checkbox_array[index] = [div, string_distance, label_text];
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

  // Build up elements to put on page
  checkbox_group.replaceChildren();
  checkbox_array.forEach((checkbox_div) => {
    checkbox_group.append(checkbox_div[0]);
  });

}