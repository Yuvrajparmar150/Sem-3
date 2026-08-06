import java.util.Random;
import java.util.Scanner;
public class RPSLS {
enum Move {
    ROCK, PAPER, SCISSORS, LIZARD, SPOCK
}
 static int winner(Move a, Move b) 

    {
        if (a == b) return 0;
        return switch (a) 
            {
                case SCISSORS -> (b == Move.PAPER || b == Move.LIZARD) ? 1 : -1;
                case PAPER    -> (b == Move.ROCK || b == Move.SPOCK) ? 1 : -1;
                case ROCK     -> (b == Move.LIZARD || b == Move.SCISSORS) ? 1 : -1;
                case LIZARD   -> (b == Move.SPOCK || b == Move.PAPER) ? 1 : -1;
                case SPOCK    -> (b == Move.SCISSORS || b == Move.ROCK) ? 1 : -1;
            };
        
        }

    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);
        Random rand = new Random();
        int playerScore = 0, computerScore = 0;
        System.out.println("=======Welcome to Rock Paper Scissors Lizard Spock!=======");
        
        for(int round=1;round<=5;round++)
        {
            System.out.print("Round " + round + ": Enter your move (ROCK, PAPER, SCISSORS, LIZARD, SPOCK): ");
            String input = sc.nextLine().toUpperCase();
            Move playerMove=Move.valueOf(input);
            Move computerMove = Move.values()[rand.nextInt(Move.values().length)];
            System.out.print("You: " + playerMove + ", Computer: " + computerMove + ". ");
            int result = winner(playerMove, computerMove);
            if(result == 1) {
                System.out.println("You win this round!");
                System.out.println(" ");
                playerScore++;
            } else if(result == -1) {
                System.out.println("Computer wins this round!");
                System.out.println(" ");
                computerScore++;
            } else {
                System.out.println("This round is a tie!");
                System.out.println(" ");
            }
        }
        System.out.println("=======Game Over!=======");

        System.out.println("Final Scores - You: " + playerScore + ", Computer: " + computerScore + ", Ties: " + (5 - playerScore - computerScore));
        if(playerScore > computerScore) {
            System.out.println("Congratulations! You won the game!");
        } else if(playerScore < computerScore) {
            System.out.println("Computer wins the game! Better luck next time.");
        } else {
            System.out.println("The game is a tie!");
        }
        sc.close();
    }
 }