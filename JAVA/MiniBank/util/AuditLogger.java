package util;

/** PR-8: small AutoCloseable used in a try-with-resources example. */
public class AuditLogger implements AutoCloseable {
    private final String name;

    public AuditLogger(String name) {
        this.name = name;
        System.out.println("[" + name + "] audit log opened");
    }

    public void log(String message) {
        System.out.println("[" + name + "] " + message);
    }

    @Override
    public void close() {
        System.out.println("[" + name + "] audit log closed");
    }
}
