package ShapeAreas;

import java.util.Scanner;
abstract class Shape {
    abstract double getArea();
    static class Circle extends Shape 
    {
        private double radius;

        public Circle(double radius) 
        {
            this.radius = radius;
        }
        @Override
        double getArea() 
        {
            return Math.PI * radius * radius;
        }
    }
    static class Rectangle extends Shape 
    {
        private double length;
        private double width;

        public Rectangle(double length, double width) 
        {
            this.length = length;
            this.width = width;
        }
        @Override
        double getArea() 
        {
            return length*width;
        }
    }
    static class Triangle extends Shape
    {
        private double base;
        private double height;

        public Triangle(double base,double height)
        {
            this.base=base;
            this.height=height;
        }
        @Override
        double getArea()
        {
            return 0.5 * base * height;
        }
    }

    public static void main(String[] args) {
    Scanner sc = new Scanner(System.in);

    System.out.println("Enter the radius of the circle:");
    double radius = sc.nextDouble();
    Shape circle = new Shape.Circle(radius);

    System.out.println("Enter the length and width of the rectangle:");
    double length = sc.nextDouble();
    double width = sc.nextDouble();
    Shape rectangle = new Shape.Rectangle(length, width);

    System.out.println("Enter the base and height of the triangle:");
    double base = sc.nextDouble();
    double height = sc.nextDouble();
    Shape triangle = new Shape.Triangle(base, height);

   
    Shape[] shapes = {circle, rectangle, triangle};

    double totalArea = 0;
    double largestArea = Double.MIN_VALUE;
    Shape largestShape = null;

    
    for (Shape s : shapes) {
        double area = s.getArea();
        System.out.println("Area: " + area);
        totalArea += area;

        if (area > largestArea) {
            largestArea = area;
            largestShape = s;
        }
    }

    System.out.println("Total Area of all shapes: " + totalArea);
    System.out.println("Largest Area: " + largestArea);
 }
}