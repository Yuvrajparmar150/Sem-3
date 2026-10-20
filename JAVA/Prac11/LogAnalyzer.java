import java.io.IOException;
import java.nio.file.*;
import java.nio.file.attribute.BasicFileAttributes;
import java.util.List;

public class LogAnalyzer {
    public static void main(String[] args) throws IOException {
        Path dir = Paths.get("logs");
        Files.createDirectories(dir);
        Files.write(dir.resolve("a.log"), List.of("INFO started", "ERROR disk full", "INFO running"));
        Files.write(dir.resolve("b.log"), List.of("ERROR timeout", "ERROR timeout again", "INFO ok"));
        Files.write(dir.resolve("c.log"), List.of("INFO all good"));

        String keyword = "ERROR";
        int totalLines = 0, matches = 0;

        try (DirectoryStream<Path> files = Files.newDirectoryStream(dir, "*.log")) {
            for (Path file : files) {
                List<String> lines = Files.readAllLines(file);
                totalLines += lines.size();
                for (String line : lines) {
                    if (line.contains(keyword)) matches++;
                }
                BasicFileAttributes attrs = Files.readAttributes(file, BasicFileAttributes.class);
                System.out.println(file.getFileName() + " -> " + lines.size() + " lines, " + attrs.size() + " bytes");
            }
        }
        System.out.println("Total lines: " + totalLines);
        System.out.println("Lines containing \"" + keyword + "\": " + matches);
    }
}
