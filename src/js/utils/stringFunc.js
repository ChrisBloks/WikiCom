
//https://stackoverflow.com/questions/1026069/how-do-i-make-the-first-letter-of-a-string-uppercase-in-javascript
export const stringFunc = {
    lowerCaseFirst(text) {
        return String(text).charAt(0).toLowerCase() + String(text).slice(1);
    },

    upperCaseFirst(text) {
        return String(text).charAt(0).toUpperCase() + String(text).slice(1);
    }
}