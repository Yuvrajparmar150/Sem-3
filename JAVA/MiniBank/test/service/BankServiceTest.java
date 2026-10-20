package service;

import exception.InsufficientFundsException;
import model.Account;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.io.TempDir;

import java.nio.file.Path;
import java.util.ArrayList;
import java.util.List;

import static org.junit.jupiter.api.Assertions.*;

class BankServiceTest {

    @Test
    void depositIncreasesTheBalance(@TempDir Path dir) throws Exception {
        BankService service = new BankService(dir);
        Account a = service.openAccount("SAVINGS", "Asha Patel", "9876543210", 5000, 1000);
        service.deposit(a.getAccountNumber(), 2000);
        assertEquals(7000, a.getBalance());
    }

    @Test
    void withdrawalOverTheLimitThrowsException(@TempDir Path dir) throws Exception {
        BankService service = new BankService(dir);
        Account a = service.openAccount("SAVINGS", "Asha Patel", "9876543210", 5000, 1000);
        InsufficientFundsException e = assertThrows(InsufficientFundsException.class,
                () -> service.withdraw(a.getAccountNumber(), 5000));
        assertEquals(1000, e.getShortfall());
        assertEquals(5000, a.getBalance());              // balance unchanged
    }

    @Test
    void invalidMobileNumberIsRejected(@TempDir Path dir) {
        BankService service = new BankService(dir);
        assertThrows(IllegalArgumentException.class,
                () -> service.openAccount("SAVINGS", "Asha Patel", "12345", 5000, 1000));
    }

    @Test
    void manyConcurrentDepositsKeepTheBalanceCorrect(@TempDir Path dir) throws Exception {
        BankService service = new BankService(dir);
        Account a = service.openAccount("SAVINGS", "Asha Patel", "9876543210", 1000, 0);
        List<String> commands = new ArrayList<>();
        for (int i = 0; i < 2000; i++) {
            commands.add("DEPOSIT " + a.getAccountNumber() + " 1");
        }
        List<String> results = service.runBatch(commands);
        assertEquals(2000, results.size());
        assertEquals(3000, a.getBalance());              // 1000 + 2000 x 1, nothing lost
    }

    @Test
    void savedStateIsLoadedUnchanged(@TempDir Path dir) throws Exception {
        BankService first = new BankService(dir);
        Account a = first.openAccount("CURRENT", "Ravi Shah", "9123456780", 2000, 3000);
        first.deposit(a.getAccountNumber(), 500);
        first.save();

        BankService second = new BankService(dir);
        assertEquals(1, second.load());
        Account reloaded = second.find(a.getAccountNumber());
        assertEquals("Ravi Shah", reloaded.getOwnerName());
        assertEquals(2500, reloaded.getBalance());
    }
}
