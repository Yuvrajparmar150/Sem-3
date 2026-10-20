import java.lang.management.ManagementFactory;
import java.lang.management.ThreadMXBean;

public class DeadlockDemo {
    static final Object LOCK_A = new Object();
    static final Object LOCK_B = new Object();

    static void pause() {
        try { Thread.sleep(100); } catch (InterruptedException e) { Thread.currentThread().interrupt(); }
    }

    public static void main(String[] args) throws InterruptedException {
        // ---- 1. opposite lock order -> deadlock ----
        Thread t1 = new Thread(() -> {
            synchronized (LOCK_A) { pause(); synchronized (LOCK_B) { System.out.println("T1 done"); } }
        }, "T1");
        Thread t2 = new Thread(() -> {
            synchronized (LOCK_B) { pause(); synchronized (LOCK_A) { System.out.println("T2 done"); } }
        }, "T2");
        t1.setDaemon(true);   // daemon, so the JVM can still exit while they are stuck
        t2.setDaemon(true);
        t1.start();
        t2.start();
        t1.join(2000);
        t2.join(2000);

        ThreadMXBean mx = ManagementFactory.getThreadMXBean();
        long[] stuck = mx.findDeadlockedThreads();
        System.out.println("Opposite order : " + (stuck != null ? "DEADLOCK detected, " + stuck.length + " threads blocked" : "no deadlock"));

        // ---- 2. same order for everybody -> no deadlock (fresh locks: the old ones are still stuck) ----
        final Object lockC = new Object();
        final Object lockD = new Object();
        Thread f1 = new Thread(() -> {
            synchronized (lockC) { pause(); synchronized (lockD) { System.out.println("F1 done"); } }
        }, "F1");
        Thread f2 = new Thread(() -> {
            synchronized (lockC) { pause(); synchronized (lockD) { System.out.println("F2 done"); } }
        }, "F2");
        f1.start();
        f2.start();
        f1.join();
        f2.join();
        System.out.println("Consistent order: both threads finished normally");
    }
}
