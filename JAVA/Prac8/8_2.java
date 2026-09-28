// Stock issue: a Warehouse where issue(item, qty) throws a custom checked
// OutOfStockException carrying the shortfall, and InvalidQuantityException for qty ≤ 0.
// Process a list of requests, catching and reporting each failure without stopping the run.

class OutOfStockException extends Exception {
    private int shortfall;

    public OutOfStockException(String message, int shortfall) {
        super(message);
        this.shortfall = shortfall;
    }

    public int getShortfall() {
        return shortfall;
    }
}

class InvalidQuantityException extends Exception {
    public InvalidQuantityException(String message) {
        super(message);
    }
    }
    