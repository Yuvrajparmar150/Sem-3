/**
 * Practical 9 - Problem 1: Counter Race Condition
 * 
 * Demonstrates:
 * 1. Unsynchronized multithreaded access to a shared counter causing lost updates (race condition).
 * 2. Synchronized access ensuring thread safety and exact count correctness.
 */
public class CounterRace {
    private static int counter = 0;
    private static final int THREADS = 10;
    private static final int INCREMENTS_PER_THREAD = 100_000;

    public static void main(String[] args) throws InterruptedException {
        int expectedTotal = THREADS * INCREMENTS_PER_THREAD;

        System.out.println("=================================================");
        System.out.println("   EXPERIMENT 1: COUNTER RACE & SYNCHRONIZATION   ");
        System.out.println("=================================================");
        System.out.println("Number of Threads       : " + THREADS);
        System.out.println("Increments Per Thread   : " + INCREMENTS_PER_THREAD);
        System.out.println("Expected Final Counter  : " + expectedTotal);
        System.out.println("-------------------------------------------------");

        counter = 0;
        Thread[] unsyncThreads = new Thread[THREADS];
        for (int i = 0; i < THREADS; i++) {
            unsyncThreads[i] = new Thread(() -> {
                for (int j = 0; j < INCREMENTS_PER_THREAD; j++) {
                    
                    counter++; 
                }
            }, "Unsync-Thread-" + i);
            unsyncThreads[i].start();
        }

        for (Thread t : unsyncThreads) {
            t.join();
        }

        System.out.println("\n[1] UNSYNCHRONIZED RUN:");
        System.out.println("    Actual Result       : " + counter);
        System.out.println("    Lost Updates        : " + (expectedTotal - counter));
        System.out.println("    Status              : " + (counter == expectedTotal ? "PASSED" : "FAILED (Total is too low due to Race Condition)"));

       
        counter = 0;
        Object lock = new Object();
        Thread[] syncThreads = new Thread[THREADS];
        for (int i = 0; i < THREADS; i++) {
            syncThreads[i] = new Thread(() -> {
                for (int j = 0; j < INCREMENTS_PER_THREAD; j++) {
                    synchronized (lock) {
                        counter++;
                    }
                }
            }, "Sync-Thread-" + i);
            syncThreads[i].start();
        }

        for (Thread t : syncThreads) {
            t.join();
        }

        System.out.println("\n[2] SYNCHRONIZED RUN:");
        System.out.println("    Actual Result       : " + counter);
        System.out.println("    Lost Updates        : " + (expectedTotal - counter));
        System.out.println("    Status              : " + (counter == expectedTotal ? "PASSED (Exact match every run!)" : "FAILED"));
        System.out.println("=================================================\n");
    }
}
