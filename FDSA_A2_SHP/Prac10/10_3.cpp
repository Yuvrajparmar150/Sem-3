#include <iostream>
using namespace std;

#define SIZE 10

int hash1(int key) {
    return (key % SIZE + SIZE) % SIZE;
}

int hash2(int key) {
    return 7 - ((key % 7 + 7) % 7);
}

int main() {
    int hashTable[SIZE];

    for (int i = 0; i < SIZE; i++) {
        hashTable[i] = -1;
    }

    int n, id;

    cout << "Enter number of students: ";
    cin >> n;

    for (int i = 0; i < n; i++) {
        cout << "Enter student ID: ";
        cin >> id;

        int index = hash1(id);
        int step = hash2(id);
        bool inserted = false;

        for (int j = 0; j < SIZE; j++) {
            int newIndex = (index + j * step) % SIZE;

            if (hashTable[newIndex] == -1) {
                hashTable[newIndex] = id;
                inserted = true;
                break;
            }
        }

        if (!inserted) {
            cout << "Could not insert student ID "
                 << id << endl;
        }
    }

    cout << "\nFinal Hash Table:\n";

    for (int i = 0; i < SIZE; i++) {
        cout << "Slot " << i << ": ";

        if (hashTable[i] == -1)
            cout << "Empty";
        else
            cout << hashTable[i];

        cout << endl;
    }

    return 0;
}