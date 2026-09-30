public class Driver {
    public static void main(String[] args) {
        PasswordChecker checker = new PasswordChecker();
        String[] passwords = {"abc", "password", "Password1", "Abcd1234!"};

        for (String password : passwords) {
            System.out.println("Password: " + password);
            System.out.println("Length >= 8: " + checker.hasMinimumLength(password));
            System.out.println("Contains uppercase: " + checker.hasUppercaseLetter(password));
            System.out.println("Contains digit: " + checker.hasDigit(password));
            System.out.println("Contains special character: " + checker.hasSpecialCharacter(password));
            System.out.println("Strength: " + checker.strength(password));
            System.out.println();
        }
    }
}
