// Vending machine (VendingMachine.java, main in this file) 
// (a) Define an enum Coin with constants ONE, TWO, FIVE, TEN. 
// (b) In main, set a snack price of 15 and a running total of 0; create a Scanner. 
// (c) Loop: read a coin name, use a switch expression to convert the Coin to its value 
// (ONE→1, TWO→2, FIVE→5, TEN→10), add it to the total, and print the total so 
// far. 
// (d) Stop the loop once the total reaches 15 or more. 
// (e) Print the change to return (total − 15). 
// Expected output: TEN then FIVE prints “Paid. Change: 0”; TEN then TEN prints 
// “Paid. Change: 5”.

import java.util.Scanner;

enum Coin{
        ONE, TWO, FIVE, TEN
    }

public class VendingMachine {
    public static void main(String[] args) {
        int snackprice = 15;
        int total = 0;
        Scanner sc = new Scanner(System.in);
        while (total < snackprice) {
            System.out.println("Enter coin (ONE, TWO, FIVE, TEN): ");
            String CoinName = sc.nextLine().toUpperCase();
            Coin coin = Coin.valueOf(CoinName);
            int coinValue = switch (coin) {
                case ONE -> 1;
                case TWO -> 2;
                case FIVE -> 5;
                case TEN -> 10;
            };
            total += coinValue;
            System.out.println("Total so far: " + total);

            }
        System.out.println("Paid. Change: " + (total - snackprice));    
        sc.close();
    }
}