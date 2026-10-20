import java.util.*;

public class WordFrequency {
    public static void main(String[] args) {
        String paragraph = "the quick brown fox jumps over the lazy dog the fox runs and the dog sleeps";
        Map<String, Integer> freq = new HashMap<>();
        for (String word : paragraph.split("\\s+")) {
            freq.put(word, freq.getOrDefault(word, 0) + 1);
        }

        List<Map.Entry<String, Integer>> entries = new ArrayList<>(freq.entrySet());
        entries.sort(Comparator.comparing((Map.Entry<String, Integer> e) -> e.getValue()).reversed()
                               .thenComparing(Map.Entry::getKey));

        for (Map.Entry<String, Integer> e : entries) {
            System.out.println(e.getKey() + " -> " + e.getValue());
        }
    }
}
