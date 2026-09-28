import java.lang.annotation.*;
import java.lang.reflect.Field;
import java.util.ArrayList;
import java.util.List;

@Retention(RetentionPolicy.RUNTIME)
@Target(ElementType.FIELD)
@interface NotBlank {
}

@Retention(RetentionPolicy.RUNTIME)
@Target(ElementType.FIELD)
@interface MaxLength {
    int value();
}

class SignupForm {

    @NotBlank
    @MaxLength(20)
    String username;

    @NotBlank
    @MaxLength(50)
    String email;

    @NotBlank
    @MaxLength(15)
    String password;

    SignupForm(String username, String email, String password) {
        this.username = username;
        this.email = email;
        this.password = password;
    }
}

public class FormValidator {

    public static List<String> validate(Object obj) {

        List<String> errors = new ArrayList<>();

        Class<?> classType = obj.getClass();

        for (Field field : classType.getDeclaredFields()) {

            field.setAccessible(true);

            try {

                Object value = field.get(obj);

                // Check NotBlank
                if (field.isAnnotationPresent(NotBlank.class)) {

                    if (value == null || value.toString().trim().isEmpty()) {
                        errors.add(field.getName() + " cannot be blank");
                    }
                }

                // Check MaxLength
                if (field.isAnnotationPresent(MaxLength.class)
                        && value != null) {

                    MaxLength annotation =
                            field.getAnnotation(MaxLength.class);

                    int maxLength = annotation.value();

                    if (value.toString().length() > maxLength) {
                        errors.add(field.getName()
                                + " must have maximum "
                                + maxLength
                                + " characters");
                    }
                }

            } catch (IllegalAccessException e) {

                errors.add("Cannot access field: "
                        + field.getName());
            }
        }

        return errors;
    }

    public static void main(String[] args) {

        SignupForm form = new SignupForm(
                "",
                "yuvraj@gmail.com",
                "12345678"
        );

        List<String> errors = validate(form);

        if (errors.isEmpty()) {
            System.out.println("Form is valid");
        } else {

            System.out.println("Validation errors:");

            for (String error : errors) {
                System.out.println(error);
            }
        }
    }
}