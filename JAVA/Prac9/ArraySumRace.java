import java.util.Random;

/**
 * Practical 9 - Problem 3 (For Advanced Learners): Large Array Sum Race
 * 
 * Demonstrates:
 * 1. Sequential baseline to establish ground-truth sum and reference runtime.
 * 2. Unsynchronized multithreading showing race conditions and corrupted sum.
 * 3. Fix 1: Fine-grained synchronization (Lock on every element addition) -> Correct but slow due to lock contention.
 * 4. Fix 2: Local reduction / Thread-local accumulation -> Correct and fast (optimal parallel efficiency).
 * 5. High-resolution benchmarking (System.nanoTime()) comparing execution times and speedups.
 */
public class ArraySumRace {
    private static final int SIZE = 10_000_000; // 10 million elements
    private static final int THREADS = Runtime.getRuntime().availableProcessors();
    private static final int[] numbers = new int[SIZE];

    // Shared totals for various runs
    private static long sharedTotalUnsync = 0;
    private static long sharedTotalSyncLock = 0;
    private static long sharedTotalLocalReduction = 0;

    public static void main(String[] args) throws InterruptedException {
        System.out.println("=========================================================================");
        System.out.println(" EXPERIMENT 3: MULTITHREADED ARRAY SUMMATION & BENCHMARKING ");
        System.out.println("=========================================================================");
        System.out.println("Array Size          : " + String.format("%,d", SIZE) + " integers");
        System.out.println("Available CPU Cores : " + THREADS);
        System.out.println("Working Threads     : " + THREADS);
        System.out.println("Populating array with random values (1 to 10)...");

        // Seeded random for reproducible ground truth
        Random rand = new Random(42);
        for (int i = 0; i < SIZE; i++) {
            numbers[i] = rand.nextInt(10) + 1;
        }
        System.out.println("Array initialization complete.\n");

        // -----------------------------------------------------------------
        // [0] SEQUENTIAL BASELINE (Single Thread)
        // -----------------------------------------------------------------
        long startTime = System.nanoTime();
        long groundTruthSum = 0;
        for (int i = 0; i < SIZE; i++) {
            groundTruthSum += numbers[i];
        }
        long seqDurationNs = System.nanoTime() - startTime;
        double seqDurationMs = seqDurationNs / 1_000_000.0;
        System.out.printf("[0] Single-Threaded Baseline : Sum = %,d (Time: %.2f ms)%n", groundTruthSum, seqDurationMs);

        // -----------------------------------------------------------------
        // [1] UNSYNCHRONIZED RUN (Race Condition / Corrupted Result)
        // -----------------------------------------------------------------
        sharedTotalUnsync = 0;
        Thread[] unsyncThreads = new Thread[THREADS];
        int chunkSize = SIZE / THREADS;

        startTime = System.nanoTime();
        for (int t = 0; t < THREADS; t++) {
            final int start = t * chunkSize;
            final int end = (t == THREADS - 1) ? SIZE : (t + 1) * chunkSize;
            unsyncThreads[t] = new Thread(() -> {
                for (int i = start; i < end; i++) {
                    sharedTotalUnsync += numbers[i]; // Data race on shared variable
                }
            });
            unsyncThreads[t].start();
        }
        for (Thread t : unsyncThreads) t.join();
        long unsyncDurationNs = System.nanoTime() - startTime;
        double unsyncDurationMs = unsyncDurationNs / 1_000_000.0;

        // -----------------------------------------------------------------
        // [2] FIX 1: FINE-GRAINED SYNCHRONIZATION (Lock Per Element Addition)
        // -----------------------------------------------------------------
        sharedTotalSyncLock = 0;
        Object lock = new Object();
        Thread[] syncLockThreads = new Thread[THREADS];

        startTime = System.nanoTime();
        for (int t = 0; t < THREADS; t++) {
            final int start = t * chunkSize;
            final int end = (t == THREADS - 1) ? SIZE : (t + 1) * chunkSize;
            syncLockThreads[t] = new Thread(() -> {
                for (int i = start; i < end; i++) {
                    synchronized (lock) {
                        sharedTotalSyncLock += numbers[i]; // Lock acquired/released on EVERY element
                    }
                }
            });
            syncLockThreads[t].start();
        }
        for (Thread t : syncLockThreads) t.join();
        long syncLockDurationNs = System.nanoTime() - startTime;
        double syncLockDurationMs = syncLockDurationNs / 1_000_000.0;

        // -----------------------------------------------------------------
        // [3] FIX 2: THREAD-LOCAL ACCUMULATION (Parallel Reduction / Map-Reduce)
        // -----------------------------------------------------------------
        sharedTotalLocalReduction = 0;
        Object mergeLock = new Object();
        Thread[] reductionThreads = new Thread[THREADS];

        startTime = System.nanoTime();
        for (int t = 0; t < THREADS; t++) {
            final int start = t * chunkSize;
            final int end = (t == THREADS - 1) ? SIZE : (t + 1) * chunkSize;
            reductionThreads[t] = new Thread(() -> {
                long localSum = 0; // Local variable stored in thread register / cache
                for (int i = start; i < end; i++) {
                    localSum += numbers[i]; // Blazing fast local computation without locks
                }
                // Merge thread's partial sum once into shared total
                synchronized (mergeLock) {
                    sharedTotalLocalReduction += localSum;
                }
            });
            reductionThreads[t].start();
        }
        for (Thread t : reductionThreads) t.join();
        long reductionDurationNs = System.nanoTime() - startTime;
        double reductionDurationMs = reductionDurationNs / 1_000_000.0;

        // -----------------------------------------------------------------
        // PERFORMANCE & ACCURACY COMPARISON SUMMARY TABLE
        // -----------------------------------------------------------------
        System.out.println("\n=========================================================================");
        System.out.println("                        BENCHMARK & RESULTS SUMMARY                      ");
        System.out.println("=========================================================================");
        System.out.printf("%-32s | %-15s | %-8s | %-12s | %-10s%n",
                "Implementation Strategy", "Calculated Sum", "Correct?", "Time (ms)", "vs Fix 1");
        System.out.println("-------------------------------------------------------------------------");

        printRow("0. Single-Thread Baseline", groundTruthSum, groundTruthSum, seqDurationMs, syncLockDurationMs / seqDurationMs);
        printRow("1. Unsynchronized (Data Race)", sharedTotalUnsync, groundTruthSum, unsyncDurationMs, syncLockDurationMs / unsyncDurationMs);
        printRow("2. Fix 1: Synchronized Lock", sharedTotalSyncLock, groundTruthSum, syncLockDurationMs, 1.0);
        printRow("3. Fix 2: Local Reduction", sharedTotalLocalReduction, groundTruthSum, reductionDurationMs, syncLockDurationMs / reductionDurationMs);

        System.out.println("=========================================================================");

        System.out.println("\nKey Engineering Takeaways:");
        System.out.println("1. Unsynchronized Multithreading: Reads & writes overlap, losing updates and resulting in incorrect sum.");
        System.out.println("2. Fix 1 (Fine-grained Synchronized Lock): Ensures correctness, but creates extreme lock contention,");
        System.out.println("   forcing parallel threads into sequential lock queues and incurring heavy synchronization overhead.");
        System.out.printf("3. Fix 2 (Thread-Local Reduction): Computes independently in CPU cache and synchronizes only once per thread,%n");
        System.out.printf("   running %.1fx faster than Fix 1 and achieving true multicore parallel speedup!%n", (syncLockDurationMs / reductionDurationMs));
        System.out.println("=========================================================================\n");
    }

    private static void printRow(String name, long sum, long expected, double timeMs, double speedupVsFix1) {
        boolean correct = (sum == expected);
        String correctStr = correct ? "YES" : "NO";
        System.out.printf("%-32s | %,15d | %-8s | %10.2f ms | %8.1fx%n",
                name, sum, correctStr, timeMs, speedupVsFix1);
    }
}
