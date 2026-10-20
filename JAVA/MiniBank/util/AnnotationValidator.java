package util;

import model.annotation.MaxLength;
import model.annotation.Positive;

import java.lang.reflect.Field;
import java.util.ArrayList;
import java.util.List;

/** PR-7: validates any object by reading its annotations with reflection. */
public class AnnotationValidator {
    private AnnotationValidator() { }

    public static String[] validate(Object obj) {
        if (obj == null) {
            throw new IllegalArgumentException("Object cannot be null.");
        }
        List<String> errors = new ArrayList<>();

        // walk up the hierarchy: SavingsAccount -> Account -> Object
        for (Class<?> c = obj.getClass(); c != null && c != Object.class; c = c.getSuperclass()) {
            for (Field field : c.getDeclaredFields()) {
                field.setAccessible(true);
                try {
                    Object value = field.get(obj);

                    if (field.isAnnotationPresent(Positive.class)
                            && value instanceof Number n && n.longValue() <= 0) {
                        errors.add(field.getName() + ": " + field.getAnnotation(Positive.class).message());
                    }
                    if (field.isAnnotationPresent(MaxLength.class)
                            && value instanceof String s) {
                        int max = field.getAnnotation(MaxLength.class).value();
                        if (s.length() > max) {
                            errors.add(field.getName() + ": length must be <= " + max);
                        }
                    }
                } catch (IllegalAccessException e) {
                    errors.add(field.getName() + ": cannot be read");
                }
            }
        }
        return errors.toArray(new String[0]);
    }
}
