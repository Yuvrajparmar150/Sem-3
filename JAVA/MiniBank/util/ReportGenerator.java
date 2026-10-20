package util;

import java.io.BufferedReader;
import java.io.IOException;
import java.nio.file.DirectoryStream;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.attribute.BasicFileAttributes;
import java.util.ArrayList;
import java.util.List;

/** PR-11: walks the logs folder and writes an end-of-day summary report. */
public class ReportGenerator {
    private ReportGenerator() { }

    public static List<String> generate(Path logsDir, Path reportFile) throws IOException {
        long totalDeposits = 0;
        long totalWithdrawals = 0;
        List<String> report = new ArrayList<>();
        report.add("===== END OF DAY REPORT =====");

        try (DirectoryStream<Path> files = Files.newDirectoryStream(logsDir, "*.log")) {
            for (Path file : files) {
                BasicFileAttributes attrs = Files.readAttributes(file, BasicFileAttributes.class);
                if (attrs.size() == 0) {
                    continue;                                   // skip empty logs
                }
                report.add("Log file: " + file.getFileName() + " (" + attrs.size()
                        + " bytes, modified " + attrs.lastModifiedTime() + ")");
                try (BufferedReader reader = Files.newBufferedReader(file)) {
                    String line;
                    while ((line = reader.readLine()) != null) {
                        String[] parts = line.trim().split("\\s+");
                        if (parts.length != 3) {
                            continue;
                        }
                        long amount = Long.parseLong(parts[2]);
                        if (parts[0].equals("DEPOSIT")) {
                            totalDeposits += amount;
                        } else if (parts[0].equals("WITHDRAW")) {
                            totalWithdrawals += amount;
                        }
                    }
                }
            }
        }
        report.add("Total deposits    : Rs." + totalDeposits);
        report.add("Total withdrawals : Rs." + totalWithdrawals);
        report.add("Net change        : Rs." + (totalDeposits - totalWithdrawals));

        if (reportFile.getParent() != null) {
            Files.createDirectories(reportFile.getParent());
        }
        Files.write(reportFile, report);
        return report;
    }
}
