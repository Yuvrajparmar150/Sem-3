/*Remote control: an interface Switchable with on()/off() and a default toggle(); Fan and
Light implement it. Loop over a Switchable[] toggling each. Then add a functional
interface that decides, given a device and an hour, whether it may switch on, and supply it
once as an anonymous class and once as a lambda. */

// Switchable interface
interface Switchable {
    void on();
    void off();

    default void toggle() {
        System.out.println("Toggling...");
        on();  // simple default: just call on()
    }
}

// Fan implementation
class Fan implements Switchable {
    private boolean running = false;

    @Override
    public void on() {
        running = true;
        System.out.println("Fan is now ON");
    }

    @Override
    public void off() {
        running = false;
        System.out.println("Fan is now OFF");
    }
}

// Light implementation
class Light implements Switchable {
    private boolean lit = false;

    @Override
    public void on() {
        lit = true;
        System.out.println("Light is now ON");
    }

    @Override
    public void off() {
        lit = false;
        System.out.println("Light is now OFF");
    }
}

// Functional interface for policy
@FunctionalInterface
interface SwitchPolicy {
    boolean maySwitchOn(Switchable device, int hour);
}

// Runner
public class RemoteControlDemo {
    public static void main(String[] args) {
        Switchable[] devices = { new Fan(), new Light() };

        // Loop over devices and toggle each
        for (Switchable s : devices) {
            s.toggle();
        }

        // Anonymous class policy: only allow switching on before 6pm
        SwitchPolicy dayPolicy = new SwitchPolicy() {
            @Override
            public boolean maySwitchOn(Switchable device, int hour) {
                return hour < 18;
            }
        };

        // Lambda policy: only allow switching on after 6pm
        SwitchPolicy nightPolicy = (device, hour) -> hour >= 18;

        System.out.println("\nPolicy checks:");
        int testHour = 20;
        for (Switchable s : devices) {
            System.out.println("At " + testHour + "h, "
                + s.getClass().getSimpleName()
                + " may switch on (dayPolicy)? " + dayPolicy.maySwitchOn(s, testHour));
            System.out.println("At " + testHour + "h, "
                + s.getClass().getSimpleName()
                + " may switch on (nightPolicy)? " + nightPolicy.maySwitchOn(s, testHour));
        }
    }
}
