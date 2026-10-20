public class GenericStack<T> {
    private Object[] data = new Object[2];
    private int size = 0;

    public void push(T item) {
        if (size == data.length) {
            Object[] bigger = new Object[data.length * 2];
            System.arraycopy(data, 0, bigger, 0, size);
            data = bigger;
        }
        data[size++] = item;
    }

    @SuppressWarnings("unchecked")
    public T pop() {
        if (size == 0) {
            throw new IllegalStateException("Stack is empty");
        }
        T item = (T) data[--size];
        data[size] = null;
        return item;
    }

    public boolean isEmpty() { return size == 0; }

    public static void main(String[] args) {
        GenericStack<Integer> numbers = new GenericStack<>();
        numbers.push(1); numbers.push(2); numbers.push(3);
        System.out.print("Integer stack pops: ");
        while (!numbers.isEmpty()) System.out.print(numbers.pop() + " ");
        System.out.println();

        GenericStack<String> words = new GenericStack<>();
        words.push("one"); words.push("two");
        System.out.print("String stack pops : ");
        while (!words.isEmpty()) System.out.print(words.pop() + " ");
        System.out.println();

        try {
            words.pop();
        } catch (IllegalStateException e) {
            System.out.println("Empty case handled: " + e.getMessage());
        }
    }
}
