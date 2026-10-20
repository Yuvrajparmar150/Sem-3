import java.util.*;

public class Leaderboard {
    public static void main(String[] args) {
        Map<String, Integer> scores = new HashMap<>();
        scores.put("Asha", 250);
        scores.put("Ravi", 310);
        scores.put("Meera", 250);
        scores.put("John", 180);
        scores.put("Priya", 310);

        int topN = 3;
        List<Map.Entry<String, Integer>> entries = new ArrayList<>(scores.entrySet());
        // highest score first, ties broken by name
        entries.sort((a, b) -> {
            int byScore = Integer.compare(b.getValue(), a.getValue());
            return byScore != 0 ? byScore : a.getKey().compareTo(b.getKey());
        });

        System.out.println("Top " + topN + " players:");
        for (int i = 0; i < topN && i < entries.size(); i++) {
            System.out.println((i + 1) + ". " + entries.get(i).getKey() + " - " + entries.get(i).getValue());
        }
    }
}
