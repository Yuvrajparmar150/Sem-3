// Smart thermostat (Thermostat.java, main in this file) 
// (a) Create class Thermostat with private String location, private int temperature, private 
// static final int MIN=16 and MAX=30, and private static int activeCount=0. 
// (b) Constructor(location, startTemp): store location; set temperature to startTemp if it is 
// within MIN..MAX else 22; increment activeCount. 
// (c) Constructor(location): chain with this(location, 22). 
// (d) raise(): if temperature < MAX add 1, else print “Already at maximum (30)”. 
// (e) lower(): if temperature > MIN subtract 1, else print “Already at minimum (16)”. 
// (f) Add getTemperature() (no setter) and static getActiveCount(). 
// (g) In main: create two thermostats, call raise() 10 times then lower() 20 times in loops 
// (printing the temperature each time), then print getActiveCount(). 

public class Thermostat{
    private  String location;
    private  int temperature;

    private static int MIN=16;
    private static int MAX=30;

    private static int activeCount=0;

    public Thermostat(String location, int startTemp) {
      this.location=location;
      if(startTemp>=MIN && startTemp<=MAX){
        temperature=startTemp;
      }else{
        temperature=22;
      }
      activeCount++;
    }
    public Thermostat(String location){
        this(location,22);
    }   
    public void raise(){
        if(temperature<MAX){
            temperature++;
        }else{
            System.out.println("Already at maximum (30)");
        }
    }
    public void lower(){
        if(temperature>MIN){
            temperature--;
        }else{
            System.out.println("Already at minimum (16)");
        }
    }

    public int getTemperature(){
        return temperature;
    }
    public static int getActiveCount(){
        return activeCount;
    }


    
   public static void main(String[] args) {
       Thermostat t1 = new Thermostat("Bedroom",20);
       Thermostat t2 = new Thermostat("Living Room");
       for(int i=0 ; i<10 ; i++){
          t1.raise();
          System.out.println(t1.getTemperature());
       }
       for(int i=0 ; i<20 ; i++){
         t1.lower();
         System.out.println(t1.getTemperature());
       }
       System.out.println("active count:" +Thermostat.getActiveCount());

   }

}