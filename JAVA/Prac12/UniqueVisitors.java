import java.util.*;

public class UniqueVisitors {
    public static void main(String[] args) {
        String[] logins = {"asha", "ravi", "asha", "meera", "ravi", "john", "asha", "meera"};

        Set<String> unique = new HashSet<>(Arrays.asList(logins));
        Set<String> firstSeen = new LinkedHashSet<>(Arrays.asList(logins));

        System.out.println("Total logins   : " + logins.length);
        System.out.println("Unique users   : " + unique.size());
        System.out.print("First three distinct (first-seen order): ");
        int shown = 0;
        for (String name : firstSeen) {
            if (shown++ == 3) break;
            System.out.print(name + (shown < 3 ? ", " : ""));
        }
        System.out.println();
    }
}
