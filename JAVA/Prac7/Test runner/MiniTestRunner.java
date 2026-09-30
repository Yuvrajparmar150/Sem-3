import java.lang.annotation.*;
import java.lang.reflect.*;

// --- Custom annotation ---
@Retention(RetentionPolicy.RUNTIME)
@Target(ElementType.METHOD)
@interface Run { }

// --- Class with test methods ---
class MyTests {
    @Run
    public void testOne() {
        System.out.println("Running testOne");
    }

    @Run
    public void testTwo() {
        System.out.println("Running testTwo");
    }

    public void helper() {
        System.out.println("Helper method (not annotated)");
    }
}

// --- Mini runner ---
public class MiniTestRunner {
    public static void main(String[] args) throws Exception {
        MyTests tests = new MyTests();
        int count = 0;

        for (Method m : MyTests.class.getDeclaredMethods()) {
            if (m.isAnnotationPresent(Run.class) && m.getParameterCount() == 0) {
                m.invoke(tests);   // invoke only @Run no-arg methods
                count++;
            }
        }

        System.out.println("Total @Run methods executed: " + count);
    }
}
