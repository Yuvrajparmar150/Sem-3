package app.service;

import app.model.Task;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.util.ArrayList;
import java.util.List;

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

    public void save(Path file) throws IOException {
        List<String> lines = new ArrayList<>();
        for (Task t : tasks) {
            lines.add(t.title());
        }
        Files.write(file, lines);
    }

    public void load(Path file) throws IOException {
        tasks.clear();
        for (String line : Files.readAllLines(file)) {
            tasks.add(new Task(line));
        }
    }
}
