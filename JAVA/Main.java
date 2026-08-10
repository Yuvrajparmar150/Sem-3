interface C {
    double c = 12.5;
    default void displayC() {
        System.out.println("c = " + c);
    }
}
class A {
    int a = 20;
    void displayA() {
        System.out.println("a = " + a);
    }
}
class B extends A {
    float b = 12.5f;
    void displayB() {
        System.out.println("b = " + b);
    }
}
class D extends B implements C {
    long d = 50L;
    void displayD() {
        System.out.println("d = " + d);
    }
}
class E extends D {
    void calc() {
        double result = a * b * c * d;
        System.out.println("Result = " + result);
    }
}
public class Main {
    public static void main(String[] args) {
        E obj = new E();
        obj.displayA(); 
        obj.displayB();
        obj.displayC();
        obj.displayD();
        obj.calc();
    }
}