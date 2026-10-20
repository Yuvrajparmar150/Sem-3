class Box<T> {
    private T value;
    public Box(T value) { this.value = value; }
    public T get() { return value; }
    public void set(T value) { this.value = value; }
}

class Pair<A, B> {
    private final A first;
    private final B second;
    public Pair(A first, B second) { this.first = first; this.second = second; }
    public A getFirst() { return first; }
    public B getSecond() { return second; }
    @Override
    public String toString() { return "(" + first + ", " + second + ")"; }
}

public class GenericBoxDemo {
    static <E> E firstOf(E[] items) {
        return items[0];
    }

    public static void main(String[] args) {
        Box<String> name = new Box<>("Riya");
        Box<Integer> age = new Box<>(20);
        String n = name.get();          // no cast needed
        int a = age.get();              // no cast needed
        System.out.println("Box<String>  : " + n);
        System.out.println("Box<Integer> : " + a);

        Pair<String, Double> p = new Pair<>("Balance", 5000.50);
        System.out.println("Pair         : " + p);

        System.out.println("firstOf(String[])  : " + firstOf(new String[]{"x", "y", "z"}));
        System.out.println("firstOf(Integer[]) : " + firstOf(new Integer[]{10, 20, 30}));
        // name.set(42);   // would NOT compile: Box<String> only accepts String
    }
}
