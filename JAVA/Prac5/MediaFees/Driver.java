public class Driver {
    public static void main(String[] args) {
        Media[] returnedBatch = {
            new Media.Book("The Hobbit"),
            new Media.DVD("Inception"),
            new Media.VideoGame("Racing Pro")
        };
        int[] daysLate = {2, 4, 3};
        double totalFees = 0;

        for (int index = 0; index < returnedBatch.length; index++) {
            double fee = returnedBatch[index].lateFee(daysLate[index]);
            totalFees += fee;
            System.out.println(returnedBatch[index].getTitle() + " late fee: " + fee);
        }

        System.out.println("Total late fees: " + totalFees);
    }
}
