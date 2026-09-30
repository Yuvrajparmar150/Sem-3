import java.util.Scanner;

public class Driver {
    public static void main(String[] args) {
        String[] logs = {
            "10:05 alice Hello there",
            "10:06 bob I am working",
            "malformed line"
        };

        Scanner scanner = new Scanner(System.in);
        System.out.print("Enter keyword: ");
        String keyword = scanner.nextLine();

        ChatFilter filter = new ChatFilter();
        System.out.println(filter.filter(logs, keyword));
    }
}
