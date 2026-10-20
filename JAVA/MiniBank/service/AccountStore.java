package service;

import exception.AccountNotFoundException;
import model.Account;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.HashMap;

/** PR-12: accounts kept in a HashMap keyed by account number, plus a transaction history list. */
public class AccountStore {
    private final HashMap<String, Account> accounts = new HashMap<>();
    private final ArrayList<String> history = new ArrayList<>();

    public void add(Account account) {
        accounts.put(account.getAccountNumber(), account);
        history.add("ADDED " + account.getAccountNumber() + " (" + account.getOwnerName() + ")");
    }

    public Account get(String accountNumber) throws AccountNotFoundException {
        Account account = accounts.get(accountNumber);
        if (account == null) {
            throw new AccountNotFoundException(accountNumber);
        }
        history.add("LOOKUP " + accountNumber);
        return account;
    }

    public ArrayList<Account> getAll() {
        return new ArrayList<>(accounts.values());
    }

    public ArrayList<String> getHistory() {
        return history;
    }

    /** Highest balance first. */
    public ArrayList<Account> sortedByBalance() {
        ArrayList<Account> list = getAll();
        list.sort(Comparator.comparingLong(Account::getBalance).reversed());
        return list;
    }

    /** Supplementary: every account with a balance above the given value. */
    public ArrayList<Account> balanceAbove(long limit) {
        ArrayList<Account> result = new ArrayList<>();
        for (Account a : accounts.values()) {
            if (a.getBalance() > limit) {
                result.add(a);
            }
        }
        return result;
    }
}
