public class Driver {
    public static void main(String[] args) {
        Employee[] employees = {
            new Employee.FullTime("Asha", 101, 60000),
            new Employee.PartTime("Bilal", 102, 80, 250),
            new Employee.Intern("Chen", 103, 12000)
        };

        double totalSalary = 0;

        for (Employee employee : employees) {
            double salary = employee.monthlySalary();
            totalSalary += salary;

            System.out.println(employee.getName() + " (ID: " + employee.getId() + ") salary: " + salary);
            if (employee instanceof Employee.Intern) {
                System.out.println("Note: this employee is an intern.");
            }
        }

        System.out.println("Total monthly payroll: " + totalSalary);
    }
}
