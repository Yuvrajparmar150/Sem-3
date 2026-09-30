public class ParkingLot {
    private int twoWheelers;
    private int fourWheelers;
    private final int twoCap;
    private final int fourCap;
    private static long revenue = 0;

    public ParkingLot(int twoCap, int fourCap) {
        this.twoCap = twoCap;
        this.fourCap = fourCap;
        this.twoWheelers = 0;
        this.fourWheelers = 0;
    }

    // (b) park method
    public void park(String type) {
        if (type.equalsIgnoreCase("two")) {
            if (twoWheelers < twoCap) {
                twoWheelers++;
                revenue += 20;
                System.out.println("Two-wheeler parked. Revenue +20");
            } else {
                System.out.println("Two-wheeler section Full");
            }
        } else if (type.equalsIgnoreCase("four")) {
            if (fourWheelers < fourCap) {
                fourWheelers++;
                revenue += 40;
                System.out.println("Four-wheeler parked. Revenue +40");
            } else {
                System.out.println("Four-wheeler section Full");
            }
        } else {
            System.out.println("Invalid vehicle type");
        }
    }

    // (c) leave method
    public void leave(String type) {
        if (type.equalsIgnoreCase("two")) {
            if (twoWheelers > 0) {
                twoWheelers--;
                System.out.println("Two-wheeler left.");
            } else {
                System.out.println("No two-wheelers to leave.");
            }
        } else if (type.equalsIgnoreCase("four")) {
            if (fourWheelers > 0) {
                fourWheelers--;
                System.out.println("Four-wheeler left.");
            } else {
                System.out.println("No four-wheelers to leave.");
            }
        } else {
            System.out.println("Invalid vehicle type");
        }
    }

    public void printStatus() {
        System.out.println("Current occupancy: " + twoWheelers + " two-wheelers, " +
                           fourWheelers + " four-wheelers");
        System.out.println("Total revenue: Rs." + revenue);
    }

    public static void main(String[] args) {
        ParkingLot lot = new ParkingLot(2, 2); // capacity: 2 two-wheelers, 2 four-wheelers

        // Simulate events
        lot.park("two");
        lot.park("two");
        lot.park("two"); // should be refused
        lot.park("four");
        lot.park("four");
        lot.park("four"); // should be refused

        lot.leave("two");
        lot.leave("four");
        lot.leave("four"); // one extra leave, should say none left

        // Final status
        lot.printStatus();
    }
}
