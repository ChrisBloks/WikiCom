// ============================================================
// EmailValidator — email format validation
// checks if the email is in a valid format
// ============================================================

export function EmailValidator(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}