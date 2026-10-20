package app.service;

import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.io.TempDir;

import java.io.IOException;
import java.nio.file.Path;

import static org.junit.jupiter.api.Assertions.*;

class TaskServiceTest {

    @Test
    void addingATaskIncreasesTheCount() {
        TaskService service = new TaskService();
        service.add("Buy milk");
        assertEquals(1, service.count());
    }

    @Test
    void blankTitleThrowsException() {
        assertThrows(IllegalArgumentException.class, () -> new TaskService().add(""));
    }

    @Test
    void saveAndReloadKeepsDataUnchanged(@TempDir Path dir) throws IOException {
        TaskService original = new TaskService();
        original.add("One");
        original.add("Two");
        Path file = dir.resolve("tasks.txt");
        original.save(file);

        TaskService reloaded = new TaskService();
        reloaded.load(file);
        assertEquals(original.all(), reloaded.all());
    }
}
