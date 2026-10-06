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
export function levenshtein_distance(str1, str2) {
  if (str1.length == 0) return str2.length;
  if (str2.length == 0) return str1.length;

  // Create 2d array
  var arr = Array(str2.length + 1)
    .fill(0)
    .map((_) => Array(str1.length + 1));

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
      chr1 == chr2 ? (cost = 0) : (cost = 1);

      arr[j][i] = Math.min(
        arr[j - 1][i - 1] + cost, // Substitution
        arr[j][i - 1], // Insertion is free
        arr[j - 1][i] + 1, // Deletion
      );
    }
  }
  return arr[str2.length][str1.length];
}