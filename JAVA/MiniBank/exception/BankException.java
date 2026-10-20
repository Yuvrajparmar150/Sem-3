package exception;

/** Checked base class for every MiniBank failure (PR-8). */
public class BankException extends Exception {
    private static final long serialVersionUID = 1L;

    public BankException(String message) {
        super(message);
    }
}
