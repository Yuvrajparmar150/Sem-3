import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

class TaskServiceTest {

    @Test
    void addingATaskIncreasesTheCount() {
        TaskService service = new TaskService();
        service.add("Buy milk");
        service.add("Write report");
        assertEquals(2, service.count());
    }

    @Test
    void blankTitleThrowsException() {
        TaskService service = new TaskService();
        assertThrows(IllegalArgumentException.class, () -> service.add("   "));
        assertEquals(0, service.count());
    }

    @Test
    void titleIsTrimmed() {
        TaskService service = new TaskService();
        service.add("  Pay bills  ");
        assertEquals("Pay bills", service.all().get(0).title());
    }
}
