// A government office has a token counter that issues tokens to visitors. The counter can hold at 
// most n tokens at a time. New visitors join from one end and are served from the other end in the 
// order they arrived. If the counter is full, no new token can be issued, and if it is empty, no one 
// can be served. Given a sequence of join and serve operations, implement this fixed-capacity 
// token system and print the current front token after each operation. Report an error if an 
// operation cannot be performed

#include <iostream>
using namespace std;

class TokenCounter {
private:
    int* tokens; // Array to hold tokens
    int capacity; // Maximum number of tokens
    int front; // Index of the front token
    int rear; // Index of the rear token
    int count; // Current number of tokens
public:
    TokenCounter(int n) : capacity(n), front(0), rear(-1), count(0) {
        tokens = new int[capacity];
    }

    ~TokenCounter() {
        delete[] tokens;
    }

    void join(int token) {
        if (count == capacity) {
            cout << "Error: Counter is full. Cannot issue new token." << endl;
            return;
        }
        rear = (rear + 1) % capacity;
        tokens[rear] = token;
        count++;
        printFront();
    }

    void serve() {
        if (count == 0) {
            cout << "Error: Counter is empty. No token to serve." << endl;
            return;
        }
        front = (front + 1) % capacity;
        count--;
        printFront();
    }

    void printFront() {
        if (count == 0) {
            cout << "Counter is empty." << endl;
        } else {
            cout << "Current front token: " << tokens[front] << endl;
        }
    }
    void display() {
        if (count == 0) {
            cout << "Counter is empty." << endl;
            return;
        }
        cout << "Tokens in counter: ";
        for (int i = 0; i < count; i++) {
            int index = (front + i) % capacity;
            cout << tokens[index] << " ";
        }
        cout << endl;
    }

};

int main() {
    int n;
    cout << "Enter the maximum number of tokens the counter can hold: ";
    cin >> n;

    TokenCounter counter(n);
    int choice, token;

    do {
        cout << "\nMenu:\n";
        cout << "1. Join (Issue Token)\n";
        cout << "2. Serve (Serve Token)\n";
        cout << "3. Display Tokens\n";
        cout << "4. Exit\n";
        cout << "Enter your choice: ";
        cin >> choice;

        switch (choice) {
            case 1:
                cout << "Enter token number to issue: ";
                cin >> token;
                counter.join(token);
                break;
            case 2:
                counter.serve();
                break;
            case 3:
                counter.display();
                break;
            case 4:
                cout << "Exiting..." << endl;
                break;
            default:
                cout << "Invalid choice. Please try again." << endl;
        }
    } while (choice != 4);

    return 0;
}