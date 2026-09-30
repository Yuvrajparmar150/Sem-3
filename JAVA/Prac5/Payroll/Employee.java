abstract class Employee {
    private final String name;
    private final int id;

    Employee(String name, int id) {
        this.name = name;
        this.id = id;
    }

    String getName() {
        return name;
    }

    int getId() {
        return id;
    }

    abstract double monthlySalary();

    static class FullTime extends Employee {
        private final double salary;

        FullTime(String name, int id, double salary) {
            super(name, id);
            this.salary = salary;
        }

        @Override
        double monthlySalary() {
            return salary;
        }
    }

    static class PartTime extends Employee {
        private final double hours;
        private final double rate;

        PartTime(String name, int id, double hours, double rate) {
            super(name, id);
            this.hours = hours;
            this.rate = rate;
        }

        @Override
        double monthlySalary() {
            return hours * rate;
        }
    }

    static class Intern extends Employee {
        private final double stipend;

        Intern(String name, int id, double stipend) {
            super(name, id);
            this.stipend = stipend;
        }

        @Override
        double monthlySalary() {
            return stipend;
        }
    }
}
