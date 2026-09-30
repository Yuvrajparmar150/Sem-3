// Functional interface
@FunctionalInterface
interface Notifier {
    void send(String message);
}

// Marker interface
interface Urgent { }

// Runner
public class NotificationDemo {
    public static void main(String[] args) {
        // Email sender (urgent)
        Notifier emailSender = (Notifier & Urgent) msg ->
            System.out.println("Email: " + msg);

        // SMS sender (not urgent)
        Notifier smsSender = msg ->
            System.out.println("SMS: " + msg);

        // Hold them in an array
        Notifier[] senders = { emailSender, smsSender };

        // Broadcast a message
        String message = "System update at 5 PM";

        for (Notifier n : senders) {
            n.send(message);
            // If marked urgent, send twice
            if (n instanceof Urgent) {
                n.send("[URGENT] " + message);
            }
        }
    }
}
