// A cafeteria stacks clean trays on a fixed-size counter. New trays are always placed on top, and 
// customers always take from the top. The counter can hold at most n trays at a time — if it is full, 
// no more trays can be added, and if it is empty, no tray can be taken. Given a sequence of place 
// and take operations, implement this fixed-capacity tray stack and print the current top tray after 
// each operation. Report an error if a place or take operation cannot be performed

#include <iostream>
#include <string>
#include <stack>

using namespace std;

class TrayStack {
private:
    stack<string> trays;
    int capacity;

public:
    TrayStack(int n) {
        capacity = n;
    }

    void placeTray(string trayID) {
        if (trays.size() == capacity) {
            cout << "Error: Stack is full" << endl;
            return;
        }
        trays.push(trayID);
        cout << trays.top() << endl;
    }

    void takeTray() {
        if (trays.empty()) {
            cout << "Error: Stack is empty" << endl;
            return;
        }
        trays.pop();
        if (trays.empty()) {
            cout << "Empty" << endl;
        } else {
            cout << trays.top() << endl;
        }
    }
};

int main() {
    TrayStack counter(3);

    counter.placeTray("Tray1");
    counter.placeTray("Tray2");
    counter.placeTray("Tray3");
    counter.placeTray("Tray4");

    counter.takeTray();
    counter.takeTray();
    counter.takeTray();
    counter.takeTray();

    return 0;
}