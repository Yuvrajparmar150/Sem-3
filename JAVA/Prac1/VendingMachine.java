

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
