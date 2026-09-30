public class ChatFilter {
    public String filter(String[] lines, String keyword) {
        int matches = 0;
        StringBuilder report = new StringBuilder();
        String searchTerm = keyword.toLowerCase();

        for (String line : lines) {
            String[] parts = line.split(" ", 3);
            if (parts.length < 3) continue;

            String message = parts[2];
            if (message.toLowerCase().contains(searchTerm)) {
                matches++;
                report.append(parts[0]).append(" ")
                      .append(parts[1]).append(": ")
                      .append(message).append(System.lineSeparator());
            }
        }

        return "Matches: " + matches + System.lineSeparator() + report;
    }
}
