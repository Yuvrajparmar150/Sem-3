package util;

public class CommandParser {
    private CommandParser() { }

    /** Parses a line such as "DEPOSIT AC0001 500". */
    public static Command parse(String line) {
        if (line == null) {
            throw new IllegalArgumentException("Command cannot be null.");
        }
        String[] parts = line.trim().split("\\s+");
        if (parts.length != 3) {
            throw new IllegalArgumentException("Expected: <TYPE> <ACCOUNT> <AMOUNT>");
        }
        try {
            TransactionType type = TransactionType.valueOf(parts[0].toUpperCase());
            long amount = Long.parseLong(parts[2]);
            return new Command(type, parts[1], amount);
        } catch (IllegalArgumentException e) {   // also covers NumberFormatException
            throw new IllegalArgumentException("Invalid command: " + line, e);
        }
    }
}
