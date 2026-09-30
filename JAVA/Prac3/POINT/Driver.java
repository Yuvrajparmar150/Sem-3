public class Driver {
    public static void main(String[] args) {
        // Create array with repeated points
        Point[] points = {
            new Point(1, 2),
            new Point(3, 4),
            new Point(1, 2), // repeat
            new Point(5, 6),
            new Point(3, 4), // repeat
        };

        int distinctCount = 0;

        // Check distinct points manually
        for (int i = 0; i < points.length; i++) {
            boolean seenBefore = false;
            for (int j = 0; j < i; j++) {
                if (points[i].equals(points[j])) {
                    seenBefore = true;
                    break;
                }
            }
            if (!seenBefore) {
                distinctCount++;
            }
        }

        System.out.println("Distinct: " + distinctCount);
    }
}
