package JAVA.Prac8.Part2;

// a Warehouse where issue(item, qty) throws a custom checked
// OutOfStockException carrying the shortfall, and InvalidQuantityException for qty ≤ 0.
// Process a list of requests, catching and reporting each failure without stopping the run.


class Request {

    String item;
    int qty;

    public Request(String item, int qty) {
        this.item = item;
        this.qty = qty;
    }
}

class OutOfStockException extends Exception {

    public OutOfStockException(String message) {
        super(message);
    }
}

class InvalidQuantityException extends Exception {

    public InvalidQuantityException(String message) {
        super(message);
    }
}

class Warehouse {

    public void issue(String item, int qty)
            throws OutOfStockException, InvalidQuantityException {

        if (qty <= 0) {
            throw new InvalidQuantityException(
                "Invalid quantity: " + qty
            );
        }

        if (qty > 10) {
            int shortfall = qty - 10;

            throw new OutOfStockException(
                "Out of stock for " + item +
                ". Shortfall: " + shortfall
            );
        }
    }
}

public class StockIssue {

    public static void main(String[] args) {

        Warehouse warehouse = new Warehouse();

        Request[] requests = {
            new Request("ItemA", 5),
            new Request("ItemB", 15),
            new Request("ItemC", -3),
            new Request("ItemD", 8)
        };

        for (Request request : requests) {

            try {

                warehouse.issue(request.item, request.qty);

                System.out.println(
                    "Issued " + request.qty +
                    " of " + request.item
                );

            } catch (OutOfStockException e) {

                System.out.println("Error: " + e.getMessage());

            } catch (InvalidQuantityException e) {

                System.out.println("Error: " + e.getMessage());
            }
        }
    }
}