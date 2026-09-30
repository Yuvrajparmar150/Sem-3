package ShapeAreas;

abstract class Employee {
    abstract double monthlysalary();

    static class fulltime extends Employee 
    {
        private double salary;

        public fulltime(double salary)
        {
            this.salary=salary;
        }
        @Override
        double monthlysalary()
        {
            return salary;
        }
    }

    static class parttime extends Employee
    {
        private double rate;
        private  int hours;

        public parttime(double rate,int hours)
        {
            this.rate=rate;
            this.hours=hours;

        }
        @Override
        double monthlysalary()
        {
            return rate*hours;
        }
    }

}
