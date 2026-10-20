import java.io.*;

class Player implements Serializable {
    private static final long serialVersionUID = 1L;
    private String name;
    private int score;
    private transient String password;      // transient: NOT written to the file

    public Player(String name, int score, String password) {
        this.name = name;
        this.score = score;
        this.password = password;
    }

    @Override
    public String toString() {
        return name + " | score=" + score + " | password=" + password;
    }
}

public class SaveLoad {
    public static void main(String[] args) throws Exception {
        Player[] players = {
            new Player("Asha", 120, "secret1"),
            new Player("Ravi", 95, "secret2"),
            new Player("Meera", 150, "secret3")
        };

        System.out.println("Before saving:");
        for (Player p : players) System.out.println("  " + p);

        try (ObjectOutputStream out = new ObjectOutputStream(new FileOutputStream("players.dat"))) {
            out.writeObject(players);
        }

        Player[] loaded;
        try (ObjectInputStream in = new ObjectInputStream(new FileInputStream("players.dat"))) {
            loaded = (Player[]) in.readObject();
        }

        System.out.println("After loading from players.dat:");
        for (Player p : loaded) System.out.println("  " + p);
    }
}
