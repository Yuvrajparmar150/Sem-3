package app;

import app.service.TaskService;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;

public class Main {
    public static void main(String[] args) throws IOException {
        Path file = Paths.get("tasks.txt");
        TaskService service = new TaskService();
        if (Files.exists(file)) {
            service.load(file);
            System.out.println("Loaded " + service.count() + " task(s) from " + file);
        }
        service.add("Revise OOP notes");
        service.add("Finish lab-14");
        service.save(file);
        System.out.println("Saved " + service.count() + " task(s) to " + file);
        service.all().forEach(t -> System.out.println(" - " + t.title()));
    }
}
