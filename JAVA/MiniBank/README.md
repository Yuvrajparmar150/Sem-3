# MiniBank

Console banking application built across the OOP (CEUC203) lab series.

## Features
- Menu: open account, deposit, withdraw, transfer, statement, list accounts, run a batch, end-of-day report
- Account types: Savings (minimum balance), Current (overdraft), Fixed Deposit (locked)
- Validation (regex), custom exceptions, annotation + reflection validator
- Thread-safe accounts, thread-pool batch processing over a `ConcurrentHashMap`
- Saves accounts to `data/accounts.dat` on exit and reloads them on start-up
- Append-only transaction log in `data/logs/` and an NIO based report

## Project layout
```
model/       Account, SavingsAccount, CurrentAccount, FixedDepositAccount, Customer, interfaces, annotations
exception/   BankException and its subclasses
util/        Validator, CommandParser, StatementFormatter, AnnotationValidator, StatePersister, TransactionLog, ReportGenerator
repository/  Repository<T> (generic, key found through @Id)
service/     BankService, AccountStore, TransactionProcessor, MiniBank (main)
test/        JUnit 5 tests
```

## Build and run (JDK 17+)
```
javac -d out model/*.java model/annotation/*.java exception/*.java util/*.java repository/*.java service/*.java
jar cfm minibank.jar manifest.txt -C out .
java -jar minibank.jar
```

## Run the tests (JUnit 5 console launcher)
```
javac -d testout -cp "out;junit-platform-console-standalone.jar" test/service/BankServiceTest.java
java -jar junit-platform-console-standalone.jar execute --class-path "out;testout" --scan-class-path
```
(use `:` instead of `;` on Linux / macOS)

Generated files in `data/` are not committed.
