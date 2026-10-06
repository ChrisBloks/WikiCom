// ============================================================
// PasswordLengthChecker — min-length validation
// checks length of password 1
// ============================================================
export function initPasswordLengthChecker() {
  // get password
  const passwordInput = document.querySelector("#newpassword-1");

  if (!passwordInput) return;
  // get length
  const minLength = parseInt(passwordInput.dataset.minLength, 10) || 4;

  function showError(input, message) {
    const existing = input.nextElementSibling;
    if (existing && existing.classList.contains("feedback")) {
      existing.remove();
    }
    if (message) {
      input.insertAdjacentHTML(
        "afterend",
        `<div class="feedback error text-danger">${message}</div>`,
      );
    }
  }

  function validatePasswordLength() {
    if (passwordInput.length > 0 && passwordInput.length < minLength) {
      showError(
        document.querySelector("#newpassword-1"),
        `Password must be at least ${minLength} characters.`,
      );
      return false;
    }
    showError(passwordInput, "");
    return true;
  }

  document
    .querySelectorAll("#newpassword-1, #newpassword-2")
    .forEach(function (el) {
      el.addEventListener("input", validatePasswordLength);
    });

  // call function on submit password
  document.querySelector(".edit-password")?.addEventListener("submit", (e) => {
    if (!validatePasswordLength()) e.preventDefault();
  });
}