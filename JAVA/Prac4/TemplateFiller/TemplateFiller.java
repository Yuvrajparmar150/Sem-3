import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class TemplateFiller {
    private static final Pattern PLACEHOLDER = Pattern.compile("\\{(\\w+)\\}");

    public String fill(String template, String[] names, String[] values) {
        Matcher matcher = PLACEHOLDER.matcher(template);
        StringBuilder filled = new StringBuilder();
        int previousEnd = 0;

        while (matcher.find()) {
            filled.append(template, previousEnd, matcher.start());
            filled.append(findValue(matcher.group(1), names, values));
            previousEnd = matcher.end();
        }
        filled.append(template, previousEnd, template.length());
        return filled.toString();
    }

    private String findValue(String name, String[] names, String[] values) {
        for (int index = 0; index < names.length; index++) {
            if (names[index].equals(name)) return values[index];
        }
        return "[?]";
    }
}
