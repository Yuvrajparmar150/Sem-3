abstract class Media {
    private final String title;

    Media(String title) {
        this.title = title;
    }

    String getTitle() {
        return title;
    }

    abstract double lateFee(int daysLate);

    static class Book extends Media {
        Book(String title) {
            super(title);
        }

        @Override
        double lateFee(int daysLate) {
            return daysLate * 0.75;
        }
    }

    static class DVD extends Media {
        DVD(String title) {
            super(title);
        }

        @Override
        double lateFee(int daysLate) {
            return daysLate * 1.50;
        }
    }

    static class VideoGame extends Media {
        VideoGame(String title) {
            super(title);
        }

        @Override
        double lateFee(int daysLate) {
            return daysLate * 2.00;
        }
    }
}
