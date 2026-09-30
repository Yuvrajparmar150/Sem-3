public class Driver {
    public static void main(String[] args) {
        Card[] cards = new Card[5];

        // Add cards one at a time
        cards[0] = new Card("Ace", "Spades");
        cards[1] = new Card("Queen", "Hearts");
        cards[2] = new Card("King", "Diamonds");
        cards[3] = new Card("Ace", "Spades"); // duplicate
        cards[4] = new Card("Jack", "Clubs");

        // Check for duplicates as we add
        for (int i = 0; i < cards.length; i++) {
            for (int j = 0; j < i; j++) {
                if (cards[i].equals(cards[j])) {
                    System.out.println("Duplicate found: " + cards[i]);
                    // Stop after first duplicate found
                    return;
                }
            }
        }
    }
}
