import java.util.concurrent.atomic.AtomicInteger;

/**
 * Practical 9 - Problem 2: Seat Booking Race (Overselling vs Synchronization)
 * 
 * Demonstrates:
 * 1. An unsynchronized booking method where check-then-act race conditions allow overbooking (>5 seats booked).
 * 2. A synchronized book() method ensuring atomic check-and-decrement where exactly 5 threads succeed.
 */
class TicketBookingSystem {
    private final String name;
    private int seatsLeft;
    private final AtomicInteger successfulBookings = new AtomicInteger(0);
    private final AtomicInteger failedBookings = new AtomicInteger(0);

    public TicketBookingSystem(String name, int initialSeats) {
        this.name = name;
        this.seatsLeft = initialSeats;
    }

    // UNSYNCHRONIZED BOOKING: Vulnerable to Check-Then-Act Race Condition
    public boolean bookUnsynchronized(String passenger) {
        // CHECK: Multiple threads read seatsLeft > 0 simultaneously before any thread decrements it
        if (seatsLeft > 0) {
            // Simulated delay (e.g. database/network latency)
            try {
                Thread.sleep(20);
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
            }

            // ACT: Non-atomic decrement and overselling
            seatsLeft--;
            successfulBookings.incrementAndGet();
            System.out.printf("  [SUCCESS] %-12s successfully booked! (Recorded seatsLeft: %d)%n", passenger, seatsLeft);
            return true;
        } else {
            failedBookings.incrementAndGet();
            System.out.printf("  [FAILED]  %-12s booking failed! (Sold out)%n", passenger);
            return false;
        }
    }

    // SYNCHRONIZED BOOKING: Thread-Safe with Atomic Check-Then-Act
    public synchronized boolean bookSynchronized(String passenger) {
        // Only one thread enters this critical section at a time
        if (seatsLeft > 0) {
            try {
                Thread.sleep(20);
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
            }

            seatsLeft--;
            successfulBookings.incrementAndGet();
            System.out.printf("  [SUCCESS] %-12s successfully booked! (Seats remaining: %d)%n", passenger, seatsLeft);
            return true;
        } else {
            failedBookings.incrementAndGet();
            System.out.printf("  [FAILED]  %-12s booking failed! (Sold out)%n", passenger);
            return false;
        }
    }

    public int getSeatsLeft() {
        return seatsLeft;
    }

    public int getSuccessfulBookings() {
        return successfulBookings.get();
    }

    public int getFailedBookings() {
        return failedBookings.get();
    }
}

public class SeatBookingRace {
    private static final int INITIAL_SEATS = 5;
    private static final int TOTAL_PASSENGERS = 10;

    public static void main(String[] args) throws InterruptedException {
        System.out.println("=================================================");
        System.out.println("   EXPERIMENT 2: SEAT BOOKING RACE CONDITION     ");
        System.out.println("=================================================");
        System.out.println("Initial Seats Available : " + INITIAL_SEATS);
        System.out.println("Total Passenger Threads : " + TOTAL_PASSENGERS);
        System.out.println("-------------------------------------------------");

        // ==========================================
        // 1. RUN WITHOUT SYNCHRONIZATION (OVERSELLING)
        // ==========================================
        System.out.println("\n--- [1] UNSYNCHRONIZED RUN (Overselling Occurs) ---");
        TicketBookingSystem unsafeSystem = new TicketBookingSystem("Unsafe-System", INITIAL_SEATS);
        Thread[] unsafeThreads = new Thread[TOTAL_PASSENGERS];

        for (int i = 0; i < TOTAL_PASSENGERS; i++) {
            final String passengerName = "Passenger-" + (i + 1);
            unsafeThreads[i] = new Thread(() -> {
                unsafeSystem.bookUnsynchronized(passengerName);
            });
            unsafeThreads[i].start();
        }

        for (Thread t : unsafeThreads) {
            t.join();
        }

        System.out.println("\nUnsynchronized Summary:");
        System.out.println("  Successful Bookings : " + unsafeSystem.getSuccessfulBookings() + " (Capacity exceeded: > " + INITIAL_SEATS + " seats confirmed!)");
        System.out.println("  Failed Bookings     : " + unsafeSystem.getFailedBookings());
        System.out.println("  Seats Left in State : " + unsafeSystem.getSeatsLeft());

        // ==========================================
        // 2. RUN WITH SYNCHRONIZED book() METHOD
        // ==========================================
        System.out.println("\n--- [2] SYNCHRONIZED RUN (Strict Limit of " + INITIAL_SEATS + " Seats) ---");
        TicketBookingSystem safeSystem = new TicketBookingSystem("Safe-System", INITIAL_SEATS);
        Thread[] safeThreads = new Thread[TOTAL_PASSENGERS];

        for (int i = 0; i < TOTAL_PASSENGERS; i++) {
            final String passengerName = "Passenger-" + (i + 1);
            safeThreads[i] = new Thread(() -> {
                safeSystem.bookSynchronized(passengerName);
            });
            safeThreads[i].start();
        }

        for (Thread t : safeThreads) {
            t.join();
        }

        System.out.println("\nSynchronized Summary:");
        System.out.println("  Successful Bookings : " + safeSystem.getSuccessfulBookings() + " (Exactly " + INITIAL_SEATS + " succeeded)");
        System.out.println("  Failed Bookings     : " + safeSystem.getFailedBookings() + " (Rejected as sold out)");
        System.out.println("  Seats Left in State : " + safeSystem.getSeatsLeft() + " (Correctly 0)");
        System.out.println("=================================================\n");
    }
}
