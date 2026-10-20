package service;

import exception.BankException;
import model.*;

import java.io.IOException;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

public class MiniBank {

    public static void main(String[] args) throws IOException, ClassNotFoundException {
        Scanner sc = new Scanner(System.in);
        BankInfo bank = new BankInfo("MiniBank", "Main Branch");
        System.out.println("==========================================");
        System.out.println("  " + bank);
        System.out.println("==========================================");

        BankService service = new BankService(Paths.get("data"));
        int loaded = service.load();                         // PR-11: load saved accounts on start-up
        if (loaded > 0) {
            System.out.println("Loaded " + loaded + " saved account(s).");
        }

        runMenu(sc, bank, service);

        service.save();                                      // PR-11: save on exit
        System.out.println("Accounts saved to data/accounts.dat");
        sc.close();
    }

    private static void runMenu(Scanner sc, BankInfo bank, BankService service) {
        MenuOption option;
        do {
            System.out.println("\n--- MAIN MENU ---");
            for (MenuOption m : MenuOption.values()) {
                System.out.println((m.ordinal() + 1) + ". " + m);
            }
            System.out.print("Enter choice: ");

            if (!sc.hasNextLine()) {                         // input closed -> behave like EXIT
                option = MenuOption.EXIT;
            } else {
                option = readOption(sc.nextLine());
            }
            if (option == null) {
                System.out.println("Invalid choice. Please enter a number from 1 to " + MenuOption.values().length + ".");
                continue;
            }

            try {
                switch (option) {
                    case OPEN_ACCOUNT -> openAccount(sc, service);
                    case DEPOSIT -> {
                        service.deposit(ask(sc, "Account number: "), readLong(sc, "Amount: "));
                        System.out.println("Deposit successful.");
                    }
                    case WITHDRAW -> {
                        service.withdraw(ask(sc, "Account number: "), readLong(sc, "Amount: "));
                        System.out.println("Withdrawal successful.");
                    }
                    case TRANSFER -> {
                        service.transfer(ask(sc, "From account: "), ask(sc, "To account: "), readLong(sc, "Amount: "));
                        System.out.println("Transfer successful.");
                    }
                    case STATEMENT -> System.out.println(service.statement(ask(sc, "Account number: ")));
                    case LIST_ACCOUNTS -> {
                        for (Account a : service.listAccounts()) {
                            System.out.println("  " + a.getAccountNumber() + " | " + a.getOwnerName()
                                    + " | " + a.getClass().getSimpleName() + " | Rs." + a.getBalance());
                        }
                    }
                    case RUN_BATCH -> runBatch(sc, service);
                    case REPORT -> service.report().forEach(System.out::println);
                    case EXIT -> System.out.println("Goodbye! Thank you for using " + bank.name() + ".");
                }
            } catch (BankException | IllegalArgumentException | IOException e) {
                System.out.println("Operation failed: " + e.getMessage());
            }
        } while (option != MenuOption.EXIT);
    }

    private static void openAccount(Scanner sc, BankService service) {
        String type = ask(sc, "Account type (SAVINGS / CURRENT / FD): ");
        String owner = ask(sc, "Owner name: ");
        String mobile = ask(sc, "Mobile number: ");
        long opening = readLong(sc, "Opening balance: ");
        long extra = 0;
        if (type.equalsIgnoreCase("SAVINGS")) {
            extra = readLong(sc, "Minimum balance: ");
        } else if (type.equalsIgnoreCase("CURRENT")) {
            extra = readLong(sc, "Overdraft limit: ");
        }
        Account account = service.openAccount(type, owner, mobile, opening, extra);
        System.out.println("Account opened: " + account.getAccountNumber());
    }

    private static void runBatch(Scanner sc, BankService service) {
        System.out.println("Enter commands such as DEPOSIT AC0001 500 (blank line to run):");
        List<String> commands = new ArrayList<>();
        while (true) {
            String line = sc.nextLine().trim();
            if (line.isEmpty()) {
                break;
            }
            commands.add(line);
        }
        service.runBatch(commands).forEach(System.out::println);
    }

    private static String ask(Scanner sc, String prompt) {
        System.out.print(prompt);
        return sc.nextLine().trim();
    }

    private static long readLong(Scanner sc, String prompt) {
        String text = ask(sc, prompt);
        try {
            return Long.parseLong(text);
        } catch (NumberFormatException e) {
            throw new IllegalArgumentException("Not a valid whole number: " + text);
        }
    }

    /** Converts typed text to a MenuOption; returns null for invalid input. */
    private static MenuOption readOption(String text) {
        try {
            int n = Integer.parseInt(text.trim());
            MenuOption[] all = MenuOption.values();
            if (n >= 1 && n <= all.length) {
                return all[n - 1];
            }
        } catch (NumberFormatException ignored) {
            // fall through
        }
        return null;
    }
}
