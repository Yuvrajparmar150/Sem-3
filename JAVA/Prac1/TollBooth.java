// Toll booth (TollBooth.java, main in this file) 
// (a) Define a record Vehicle(String number, String type). 
// (b) In main, keep a running toll total and three counters (bike, car, truck). 
// (c) Loop until the user types “done” for the number: build a Vehicle, use a switch 
// expression on its type for the toll (bike→20, car→50, truck→150), add to the total, 
// and increment that type’s counter. 
// (d) After the loop, print the total toll and the type with the highest count. 

import java.util.Scanner;
public class TollBooth {
    record Vehicle(String number, String type) {};

    public static void main(String[] args)
    {
        Scanner sc = new Scanner(System.in);
        int tollAmount = 0;
        int bikecount = 0;
        int carcount = 0;
        int truckcount = 0;
        while(true)
        {
            System.out.println("====== Toll Booth ======");
            System.out.println("Enter vehicle number(or 'done' to finish): ");
            String number = sc.nextLine();
            if(number.equalsIgnoreCase("done")) break;
            System.out.println("Enter vehicle type (bike, car, truck): ");
            String type = sc.nextLine();

           Vehicle v= new Vehicle(number, type);

           int toll= switch(v.type().toLowerCase())
           {
               case "bike"-> 20;
               case "car"-> 50;    
               case "truck"-> 150;
               default -> 0;
           };
           tollAmount += toll;
           switch(v.type().toLowerCase())
           {
               case "bike"-> bikecount++;
               case "car"-> carcount++;
               case "truck"-> truckcount++;
           }
        }
        System.out.println("====== Toll Summary ======");

          System.out.println("Total toll amount: " + tollAmount);
          if(bikecount>=carcount && bikecount>=truckcount)
          {
              System.out.println("Most frequent: Bike");
          }
          else if(carcount>=bikecount && carcount>=truckcount)
          {
              System.out.println("Most frequent: Car");
          }
          else
          {
              System.out.println("Most frequent: Truck");
          }
          System.out.println("====== Summary ======");
          System.out.println("Total vehicles: " + (bikecount + carcount + truckcount));
          System.out.println("Bike count: " + bikecount);
          System.out.println("Car count: " + carcount);
          System.out.println("Truck count: " + truckcount);
          sc.close();

   }
}