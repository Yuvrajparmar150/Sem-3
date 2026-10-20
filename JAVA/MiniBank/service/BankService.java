package service;

import exception.AccountNotFoundException;
import exception.BankException;
import model.*;
import repository.Repository;
import util.*;

import java.io.IOException;
import java.io.UncheckedIOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

/** PR-14: the service layer. It wires the repository, validation and exceptions together. */
public class BankService {
    private final Repository<Account> repository = new Repository<>();
    private final Path dataDir;

    public BankService(Path dataDir) {
        this.dataDir = dataDir;
    }

    private Path accountsFile() { return dataDir.resolve("accounts.dat"); }

    private Path logsDir() { return dataDir.resolve("logs"); }

    private Path logFile() { return logsDir().resolve("transactions.log"); }

    // ---------------------------------------------------------------- operations
    public Account openAccount(String type, String owner, String mobile, long opening, long extra) {
        if (!Validator.isValidMobile(mobile)) {
            throw new IllegalArgumentException("Invalid mobile number: " + mobile);
        }
        Account account = switch (type.toUpperCase()) {
            case "SAVINGS" -> new SavingsAccount(owner, opening, extra);
            case "CURRENT" -> new CurrentAccount(owner, opening, extra);
            case "FD" -> new FixedDepositAccount(owner, opening);
            default -> throw new IllegalArgumentException("Unknown account type: " + type);
        };
        String[] errors = AnnotationValidator.validate(account);
        if (errors.length > 0) {
            throw new IllegalArgumentException(String.join("; ", errors));
        }
        repository.save(account);
        return account;
    }

    public Account find(String accountNumber) throws AccountNotFoundException {
        Account account = repository.findById(accountNumber);
        if (account == null) {
            throw new AccountNotFoundException(accountNumber);
        }
        return account;
    }

    public void deposit(String accountNumber, long amount) throws BankException {
        find(accountNumber).deposit(amount);
        log("DEPOSIT", accountNumber, amount);
    }

    public void withdraw(String accountNumber, long amount) throws BankException {
        find(accountNumber).withdraw(amount);
        log("WITHDRAW", accountNumber, amount);
    }

    public void transfer(String from, String to, long amount) throws BankException {
        Account source = find(from);
        Account target = find(to);
        source.transfer(target, amount);
        log("WITHDRAW", from, amount);
        log("DEPOSIT", to, amount);
    }

    public String statement(String accountNumber) throws AccountNotFoundException {
        return StatementFormatter.buildStatement(find(accountNumber));
    }

    public List<Account> listAccounts() {
        ArrayList<Account> all = repository.findAll();
        Collections.sort(all);                       // Comparable: by account number
        return all;
    }

    // ---------------------------------------------------------------- batch processing
    /** Runs "DEPOSIT AC0001 500"-style commands on a thread pool over a ConcurrentHashMap. */
    public List<String> runBatch(List<String> commands) {
        TransactionProcessor processor = new TransactionProcessor();
        for (Account account : repository.findAll()) {
            processor.register(account);
        }
        List<String> results = Collections.synchronizedList(new ArrayList<>());
        for (String line : commands) {
            processor.submit(() -> {
                try {
                    Command cmd = CommandParser.parse(line);
                    Account account = processor.find(cmd.accountNumber());
                    if (account == null) {
                        throw new AccountNotFoundException(cmd.accountNumber());
                    }
                    switch (cmd.type()) {
                        case DEPOSIT -> account.deposit(cmd.amount());
                        case WITHDRAW -> account.withdraw(cmd.amount());
                        default -> throw new IllegalArgumentException("Batch supports DEPOSIT and WITHDRAW only");
                    }
                    TransactionLog.append(logFile(), cmd.type() + " " + cmd.accountNumber() + " " + cmd.amount());
                    results.add("OK      " + line);
                } catch (BankException | IllegalArgumentException | IOException e) {
                    results.add("FAILED  " + line + "  -> " + e.getMessage());
                }
            });
        }
        processor.stop();
        return results;
    }

    // ---------------------------------------------------------------- persistence and report
    public void save() throws IOException {
        StatePersister.save(repository.findAll().toArray(new Account[0]), accountsFile());
    }

    /** Loads saved accounts (if any) and returns how many were loaded. */
    public int load() throws IOException, ClassNotFoundException {
        if (!Files.exists(accountsFile())) {
            return 0;
        }
        Account[] saved = StatePersister.load(accountsFile());
        long highest = 0;
        for (Account account : saved) {
            repository.save(account);
            highest = Math.max(highest, Long.parseLong(account.getAccountNumber().substring(2)));
        }
        Account.continueNumberingFrom(highest);
        return saved.length;
    }

    public List<String> report() throws IOException {
        Files.createDirectories(logsDir());
        return ReportGenerator.generate(logsDir(), dataDir.resolve("report.txt"));
    }

    private void log(String type, String accountNumber, long amount) {
        try {
            TransactionLog.append(logFile(), type + " " + accountNumber + " " + amount);
        } catch (IOException e) {
            throw new UncheckedIOException(e);
        }
    }
}
