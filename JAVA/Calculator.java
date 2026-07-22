// write a code for arstorng numbr

    import java.util.Scanner;


class Addition {
    int add(int a, int b) {
        return a + b;
    }
}

class Subtraction {
    int subtract(int a, int b) {
        return a - b;
    }
}

class Multiplication {
    int multiply(int a, int b) {
        return a * b;
    }
}

class Division {
    double divide(int a, int b) {
        if (b == 0) {
            System.out.println("Division by zero is not possible.");
            return 0;
        }
        return (double) a / b;
    }
}

public class Calculator {
    public static void main(String[] args) {

        Scanner sc = new Scanner(System.in);

        System.out.print("Enter first number: ");
        int num1 = sc.nextInt();

        System.out.print("Enter second number: ");
        int num2 = sc.nextInt();


        Addition add = new Addition();
        Subtraction sub = new Subtraction();
        Multiplication mul = new Multiplication();
        Division div = new Division();

        int sum = add.add(num1, num2);
        int difference = sub.subtract(num1, num2);
        int product = mul.multiply(num1, num2);
        double quotient = div.divide(num1, num2);

    
        System.out.println("Addition       : " + sum);
        System.out.println("Subtraction    : " + difference);
        System.out.println("Multiplication : " + product);
        System.out.println("Division       : " + quotient);

        sc.close();
    }
}