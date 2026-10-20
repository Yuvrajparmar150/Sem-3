package service;

import model.Account;

import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.TimeUnit;

/** PR-10: runs banking tasks on a managed pool of 4 threads. */
public class TransactionProcessor {
    private final ExecutorService pool = Executors.newFixedThreadPool(4);

    /** PR-12: thread-safe map of the accounts shared by all pool threads. */
    private final ConcurrentHashMap<String, Account> accounts = new ConcurrentHashMap<>();

    public void register(Account account) {
        accounts.put(account.getAccountNumber(), account);
    }

    public Account find(String accountNumber) {
        return accounts.get(accountNumber);
    }

    public void submit(Runnable task) {
        pool.execute(task);
    }

    public void stop() {
        pool.shutdown();
        try {
            if (!pool.awaitTermination(30, TimeUnit.SECONDS)) {
                pool.shutdownNow();
            }
        } catch (InterruptedException e) {
            pool.shutdownNow();
            Thread.currentThread().interrupt();
        }
    }
}
