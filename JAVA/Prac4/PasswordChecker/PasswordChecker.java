public class PasswordChecker {
    public boolean hasMinimumLength(String password) {
        return password.length() >= 8;
    }

    public boolean hasUppercaseLetter(String password) {
        return password.matches(".*[A-Z].*");
    }

    public boolean hasDigit(String password) {
        return password.matches(".*\\d.*");
    }

    public boolean hasSpecialCharacter(String password) {
        return password.matches(".*[^a-zA-Z0-9].*");
    }

    public String strength(String password) {
        int passedRules = 0;
        if (hasMinimumLength(password)) passedRules++;
        if (hasUppercaseLetter(password)) passedRules++;
        if (hasDigit(password)) passedRules++;
        if (hasSpecialCharacter(password)) passedRules++;

        if (passedRules <= 1) return "Weak";
        if (passedRules <= 3) return "Medium";
        return "Strong";
    }
}
