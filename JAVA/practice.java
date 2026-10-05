
import java.util.ArrayList;

class Student {
	private int rollNo;
	private String studentName;

	Student(int rollNo, String studentName) {
		this.rollNo = rollNo;
		this.studentName = studentName;
	}

	@Override
	public String toString() {
		return "Roll No: " + rollNo + ", Student Name: " + studentName;
	}
}

public class practice {
    public static void main(String[] args) {
		ArrayList<Student> students = new ArrayList<>();

		students.add(new Student(1, "Aarav"));
		students.add(new Student(2, "Yuvraj"));
		students.add(new Student(3, "Brind"));
		students.add(new Student(4, "Prit"));
		students.add(new Student(5, "Aksh"));

		for (Student student : students) {
			System.out.println(student);
		}
    }
}