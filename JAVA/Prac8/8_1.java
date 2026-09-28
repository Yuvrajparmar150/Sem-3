// Guarded calculator: read two numbers and an operator; throw a custom
// DivideByZeroException and catch invalid-number input separately, printing a clear
// message each time; loop until a valid calculation succeeds and use finally to log each
// attempt.
import java.util.Scanner;

class DivideByZeroException extends ArithmeticException {
    public DivideByZeroException(String message) {
        super(message);
    }
}

class GuardedCalculator {
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);

        while (true) {
            try {
                System.out.print("Enter first number: ");
                double num1 = readNumber(sc);

                System.out.print("Enter operator (+, -, *, /): ");
                char operator = sc.next().charAt(0);

                System.out.print("Enter second number: ");
                double num2 = readNumber(sc);

                double result;

                switch (operator) {
                    case '+':
                        result = num1 + num2;
                        break;
                    case '-':
                        result = num1 - num2;
                        break;
                    case '*':
                        result = num1 * num2;
                        break;
                    case '/':
                        if (num2 == 0) {
                            throw new DivideByZeroException("Cannot divide by zero.");
                        }
                        result = num1 / num2;
                        break;
                    default:
                        throw new IllegalArgumentException("Invalid operator. Please use +, -, *, /.");
                }

                System.out.println("Result: " + result);
                break;

            } catch (NumberFormatException e) {
                System.out.println("Invalid number input. Please enter a valid numeric value.");
            } catch (DivideByZeroException e) {
                System.out.println("Error: " + e.getMessage());
            } catch (IllegalArgumentException e) {
                System.out.println("Error: " + e.getMessage());
            } finally {
                System.out.println("Attempt logged.\n");
            }
        }

        sc.close();
    }

    private static double readNumber(Scanner sc) {
        String input = sc.next();
        try {
            return Double.parseDouble(input);
        } catch (NumberFormatException e) {
            throw new NumberFormatException("Invalid number input. Please enter a valid numeric value.");
        }
    }
}


