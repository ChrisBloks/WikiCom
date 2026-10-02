//https://stackoverflow.com/questions/7389069/how-can-i-make-console-log-show-the-current-state-of-an-object
export function logger(obj) {
  console.log(JSON.parse(JSON.stringify(obj)));
}
