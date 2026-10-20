import java.util.Scanner;

public class Main {
    public static void main(String[] args) {
        TaskService service = new TaskService();
        Scanner sc = new Scanner(System.in);
        int choice;
        do {
            System.out.println("\n1. Add task   2. List tasks   3. Exit");
            System.out.print("Choice: ");
            choice = Integer.parseInt(sc.nextLine().trim());
            switch (choice) {
                case 1 -> {
                    System.out.print("Title: ");
                    try {
                        service.add(sc.nextLine());
                        System.out.println("Added. Total tasks: " + service.count());
                    } catch (IllegalArgumentException e) {
                        System.out.println("Error: " + e.getMessage());
                    }
                }
                case 2 -> service.all().forEach(t -> System.out.println(" - " + t.title()));
                case 3 -> System.out.println("Bye!");
                default -> System.out.println("Invalid choice");
            }
        } while (choice != 3);
        sc.close();
    }
}
