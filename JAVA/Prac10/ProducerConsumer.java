class Buffer {
    private final int[] items = new int[3];
    private int head = 0, tail = 0, count = 0;

    public synchronized void put(int value) throws InterruptedException {
        while (count == items.length) {
            wait();                       // buffer full: wait for the consumer
        }
        items[tail] = value;
        tail = (tail + 1) % items.length;
        count++;
        System.out.println("Produced " + value);
        notifyAll();
    }

    public synchronized int take() throws InterruptedException {
        while (count == 0) {
            wait();                       // buffer empty: wait for the producer
        }
        int value = items[head];
        head = (head + 1) % items.length;
        count--;
        System.out.println("   Consumed " + value);
        notifyAll();
        return value;
    }
}

public class ProducerConsumer {
    public static void main(String[] args) throws InterruptedException {
        Buffer buffer = new Buffer();
        final int TOTAL = 10;

        Thread producer = new Thread(() -> {
            try {
                for (int i = 1; i <= TOTAL; i++) {
                    buffer.put(i);
                }
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
            }
        }, "Producer");

        Thread consumer = new Thread(() -> {
            try {
                int sum = 0;
                for (int i = 1; i <= TOTAL; i++) {
                    sum += buffer.take();
                }
                System.out.println("Consumer received all " + TOTAL + " items, sum = " + sum);
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
            }
        }, "Consumer");

        producer.start();
        consumer.start();
        producer.join();
        consumer.join();
    }
}
