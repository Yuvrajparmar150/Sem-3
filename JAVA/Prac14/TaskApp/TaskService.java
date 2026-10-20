import java.util.ArrayList;

public class TaskService {
    private final ArrayList<Task> tasks = new ArrayList<>();

    public void add(String title) {
        if (title == null || title.isBlank()) {
            throw new IllegalArgumentException("Title must not be blank");
        }
        tasks.add(new Task(title.trim()));
    }

    public int count() {
        return tasks.size();
    }

    public ArrayList<Task> all() {
        return new ArrayList<>(tasks);
    }
}
