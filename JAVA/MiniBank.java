import java.util.Objects;

public class MiniBank {
    
    record BankInfo(String name, String branch) {}

    
    enum MenuOption {
        OPEN_ACCOUNT,
        DEPOSIT,
        WITHDRAW,
        TRANSFER,
        EXIT
    }

     static class Customer implements Cloneable {
        private String name;
        private String email;
        private String mobile;
        private final String customerId;
        private Address address;

        private static long customerCounter = 100;
         private static String generateCustomerId() {
            customerCounter++;
            return "CUST" + customerCounter;
        }

        public Customer(String name, String email, String mobile, Address address) {
            this.name = name;
            this.email = email;
            this.mobile = mobile;
            this.address = address;
            this.customerId = generateCustomerId();
        }


        public static class Address {
            private String line;
            private String city;
            private String pincode;

            public Address(String line, String city, String pincode) {
                this.line = line;
                this.city = city;
                this.pincode = pincode;
            }

            public String getLine() { return line; }
            public String getCity() { return city; }
            public String getPincode() { return pincode; }

            @Override
            public String toString() {
                return line + ", " + city + " - " + pincode;
            }
        }

        public String getName() { return name; }
        public String getEmail() { return email; }
        public String getMobile() { return mobile; }
        public String getCustomerId() { return customerId; }
        public Address getAddress() { return address; }

        @Override
        public Customer clone() {
            try {
                Customer copy = (Customer) super.clone();
                // Deep copy of Address
                copy.address = new Address(address.getLine(), address.getCity(), address.getPincode());
                return copy;
            } catch (CloneNotSupportedException e) {
                throw new AssertionError();
            }
        }

        @Override
        public String toString() {
            return "Customer[" + customerId + ", " + name + ", " + email + ", " + mobile + ", " + address + "]";
        }
    }

    // --- Account class ---
    static class Account {
        private final String accountNumber;
        private String ownerName;
        private long balance;
        private boolean active;

        private static long accountCounter = 0;

        private static String generateAccountNumber() {
            accountCounter++;
            return String.format("AC%04d", accountCounter);
        }

        public Account(String ownerName, long openingBalance) {
            this.ownerName = ownerName;
            this.balance = openingBalance;
            this.accountNumber = generateAccountNumber();
            this.active = true;
        }

        public Account(String ownerName) {
            this(ownerName, 0);
        }

        public void deposit(long amount) {
            if (amount > 0) balance += amount;
        }

        public boolean withdraw(long amount) {
            if (amount > 0 && balance >= amount) {
                balance -= amount;
                return true;
            }
            return false;
        }

        public String getAccountNumber() { return accountNumber; }
        public String getOwnerName() { return ownerName; }
        public long getBalance() { return balance; }
        public boolean isActive() { return active; }

        // Override toString
        @Override
        public String toString() {
            return "Account[" + accountNumber + ", Owner: " + ownerName + ", Balance: Rs." + balance + "]";
        }

        // Override equals and hashCode
        @Override
        public boolean equals(Object o) {
            if (this == o) return true;
            if (!(o instanceof Account)) return false;
            Account account = (Account) o;
            return Objects.equals(accountNumber, account.accountNumber);
        }

        @Override
        public int hashCode() {
            return Objects.hash(accountNumber);
        }
    }

     public static void main(String[] args) {
        BankInfo bank = new BankInfo("MiniBank", "Main Branch");
        System.out.println("==================================");
        System.out.println("Welcome to " + bank.name() + " - " + bank.branch());
        System.out.println("==================================");

        // Create Customers with Address
        Customer.Address addr1 = new Customer.Address("123 Street", "Thasra", "388250");
        Customer c1 = new Customer("Alice", "alice@mail.com", "9876543210", addr1);

        Customer.Address addr2 = new Customer.Address("456 Road", "Anand", "388001");
        Customer c2 = new Customer("Bob", "bob@mail.com", "9123456780", addr2);

        // Clone customer
        Customer c3 = c1.clone();

        // Create Accounts
        Account a1 = new Account(c1.getName(), 1000);
        Account a2 = new Account(c2.getName(), 500);
        Account a3 = new Account(c3.getName());

        // Print accounts using toString
        System.out.println(a1);
        System.out.println(a2);
        System.out.println(a3);

        // Compare accounts with equals
        System.out.println("a1 equals a2? " + a1.equals(a2));
        System.out.println("a1 equals a3? " + a1.equals(a3));

        // Use instanceof
        if (a1 instanceof Account) {
            System.out.println("a1 is an Account object");
        }
        if (c1 instanceof Customer) {
            System.out.println("c1 is a Customer object");
    
        }
    
    }

}