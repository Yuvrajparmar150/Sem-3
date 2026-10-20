package service;

import exception.BankException;
import model.Account;

/** PR-10: two ways to transfer money between accounts, one of which can deadlock. */
public class TransferTasks {
    private TransferTasks() { }

    private static void pause() {
        try {
            Thread.sleep(100);
        } catch (InterruptedException e) {
            Thread.currentThread().interrupt();
        }
    }

    /** Locks "from" first, then "to": two opposite transfers can deadlock. */
    public static void transferUnsafe(Account from, Account to, long amount) throws BankException {
        synchronized (from) {
            pause();
            synchronized (to) {
                from.withdraw(amount);
                to.deposit(amount);
            }
        }
    }

    /** Always locks the account with the smaller accountNumber first: no deadlock. */
    public static void transferSafe(Account from, Account to, long amount) throws BankException {
        Account first = from.getAccountNumber().compareTo(to.getAccountNumber()) < 0 ? from : to;
        Account second = (first == from) ? to : from;
        synchronized (first) {
            pause();
            synchronized (second) {
                from.withdraw(amount);
                to.deposit(amount);
            }
        }
    }
}
