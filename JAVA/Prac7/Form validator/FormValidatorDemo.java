import java.lang.annotation.*;
import java.lang.reflect.*;
import java.util.*;

// --- Custom Annotations ---
@Retention(RetentionPolicy.RUNTIME)
@Target(ElementType.FIELD)
@interface NotBlank { }

@Retention(RetentionPolicy.RUNTIME)
@Target(ElementType.FIELD)
@interface MaxLength {
    int value();
}

// --- Form Class ---
class SignupForm {
    @NotBlank
    @MaxLength(20)
    private String username;

    @NotBlank
    @MaxLength(50)
    private String email;

    private String bio; // optional, no constraints

    public SignupForm(String username, String email, String bio) {
        this.username = username;
        this.email = email;
        this.bio = bio;
    }
}

// --- Validator ---
class FormValidator {
    public static List<String> validate(Object form) {
        List<String> errors = new ArrayList<>();
        Class<?> clazz = form.getClass();

        for (Field field : clazz.getDeclaredFields()) {
            field.setAccessible(true);
            try {
                Object value = field.get(form);

                // Check @NotBlank
                if (field.isAnnotationPresent(NotBlank.class)) {
                    if (value == null || value.toString().trim().isEmpty()) {
                        errors.add(field.getName() + " must not be blank");
                    }
                }

                // Check @MaxLength
                if (field.isAnnotationPresent(MaxLength.class) && value != null) {
                    int max = field.getAnnotation(MaxLength.class).value();
                    if (value.toString().length() > max) {
                        errors.add(field.getName() + " must not exceed " + max + " characters");
                    }
                }

            } catch (IllegalAccessException e) {
                errors.add("Could not access field: " + field.getName());
            }
        }
        return errors;
    }
}

// --- Demo Runner ---
public class FormValidatorDemo {
    public static void main(String[] args) {
        SignupForm form1 = new SignupForm("Alice", "alice@example.com", "Hello!");
        SignupForm form2 = new SignupForm("", "averylongemailaddresswhichiswaytoolong@example.com", null);

        System.out.println("Form1 errors: " + FormValidator.validate(form1));
        System.out.println("Form2 errors: " + FormValidator.validate(form2));
    }
}
