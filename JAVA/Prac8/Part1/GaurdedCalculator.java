// Guarded calculator: read two numbers and an operator; throw a custom
// DivideByZeroException and catch invalid-number input separately, printing a clear
// message each time; loop until a valid calculation succeeds and use finally to log each
// attempt.
import java.util.Scanner;

class DivideByZeroException extends Exception {
    public DivideByZeroException(String message) {
        super(message);
    }
}
class GaurdedCalculator {
    public static void main(String[] args) {
       Scanner scanner = new Scanner(System.in);
        boolean validCalculation = false;

        while (!validCalculation) {
            try {
                System.out.print("Enter first number: ");
                double num1 = Double.parseDouble(scanner.nextLine());

                System.out.print("Enter second number: ");
                double num2 = Double.parseDouble(scanner.nextLine());

                System.out.print("Enter operator (+, -, *, /): ");
                String operator = scanner.nextLine();

                double result = calculate(num1, num2, operator);
                System.out.println("Result: " + result);
                validCalculation = true; // Calculation succeeded
            } catch (DivideByZeroException e) {
                System.out.println("Error: " + e.getMessage());
            } catch (NumberFormatException e) {
                System.out.println("Invalid input. Please enter valid numbers.");
            } catch (IllegalArgumentException e) {
                System.out.println("Error: " + e.getMessage());
            } finally {
                System.out.println("Attempt logged.");
            }
        }

        scanner.close();
    }

    private static double calculate(double num1, double num2, String operator) throws DivideByZeroException {
        switch (operator) {
            case "+":
                return num1 + num2;
            case "-":
                return num1 - num2;
            case "*":
                return num1 * num2;
            case "/":
                if (num2 == 0) {
                    throw new DivideByZeroException("Cannot divide by zero.");
                }
                return num1 / num2;
            default:
                throw new IllegalArgumentException("Invalid operator. Please use +, -, *, or /.");
        }
    }
}   