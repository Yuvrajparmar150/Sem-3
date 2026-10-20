package service;

/** PR-10: small shared array buffer; producer and consumer coordinate with wait()/notify(). */
public class TransactionBuffer {
    private final String[] items;
    private int head = 0;
    private int tail = 0;
    private int count = 0;

    public TransactionBuffer(int capacity) {
        this.items = new String[capacity];
    }

    public synchronized void put(String line) throws InterruptedException {
        while (count == items.length) {
            wait();                          // buffer full: wait for the consumer
        }
        items[tail] = line;
        tail = (tail + 1) % items.length;
        count++;
        notifyAll();
    }

    public synchronized String take() throws InterruptedException {
        while (count == 0) {
            wait();                          // buffer empty: wait for the producer
        }
        String line = items[head];
        items[head] = null;
        head = (head + 1) % items.length;
        count--;
        notifyAll();
        return line;
    }
}
