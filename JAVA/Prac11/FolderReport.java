import java.io.IOException;
import java.nio.file.*;
import java.nio.file.attribute.BasicFileAttributes;
import java.util.ArrayList;
import java.util.List;
import java.util.stream.Stream;

public class FolderReport {
    public static void main(String[] args) throws IOException {
        Path root = Paths.get("logs");           // run LogAnalyzer first to create it
        List<String> report = new ArrayList<>();
        report.add("Folder report for: " + root);

        long totalBytes = 0;
        try (Stream<Path> walk = Files.walk(root)) {
            for (Path p : (Iterable<Path>) walk.filter(Files::isRegularFile)::iterator) {
                BasicFileAttributes a = Files.readAttributes(p, BasicFileAttributes.class);
                totalBytes += a.size();
                report.add(p.getFileName() + " | " + a.size() + " bytes | modified " + a.lastModifiedTime());
            }
        }
        report.add("Total size: " + totalBytes + " bytes");

        Files.write(Paths.get("folder-report.txt"), report);
        report.forEach(System.out::println);
        System.out.println("Summary written to folder-report.txt");
    }
}
