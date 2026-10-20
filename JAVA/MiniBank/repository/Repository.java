package repository;

import model.annotation.Id;

import java.lang.reflect.Field;
import java.util.ArrayList;
import java.util.HashMap;

/** PR-13: one generic repository for any type; the key is the field marked with @Id. */
public class Repository<T> {
    private final HashMap<String, T> store = new HashMap<>();

    public T save(T entity) {
        store.put(idOf(entity), entity);
        return entity;
    }

    public T findById(String id) {
        return store.get(id);
    }

    public ArrayList<T> findAll() {
        return new ArrayList<>(store.values());
    }

    public boolean delete(String id) {
        return store.remove(id) != null;
    }

    public int count() {
        return store.size();
    }

    @SafeVarargs
    public final void saveAll(T... items) {
        for (T item : items) {
            save(item);
        }
    }

    /** Bounded type parameter: only Integer, Double, Long ... (subclasses of Number) are accepted. */
    public static <N extends Number> double average(N[] values) {
        double sum = 0;
        for (N v : values) {
            sum += v.doubleValue();
        }
        return values.length == 0 ? 0 : sum / values.length;
    }

    /** Reflection: look for the @Id field in the class and its superclasses. */
    private String idOf(T entity) {
        for (Class<?> c = entity.getClass(); c != null && c != Object.class; c = c.getSuperclass()) {
            for (Field field : c.getDeclaredFields()) {
                if (field.isAnnotationPresent(Id.class)) {
                    try {
                        field.setAccessible(true);
                        return String.valueOf(field.get(entity));
                    } catch (IllegalAccessException e) {
                        throw new IllegalStateException("Cannot read @Id field " + field.getName(), e);
                    }
                }
            }
        }
        throw new IllegalArgumentException("No @Id field found in " + entity.getClass().getSimpleName());
    }
}
