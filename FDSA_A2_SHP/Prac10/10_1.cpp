#include <iostream>
using namespace std;

#define SIZE 10

int main() {
    int hashTable[SIZE];

    for (int i = 0; i < SIZE; i++) {
        hashTable[i] = -1;
    }

    int n, vehicle;

    cout << "Enter number of vehicles: ";
    cin >> n;

    for (int i = 0; i < n; i++) {
        cout << "Enter vehicle registration number: ";
        cin >> vehicle;

        int index = (vehicle % SIZE + SIZE) % SIZE;
        int start = index;

        while (hashTable[index] != -1) {
            index = (index + 1) % SIZE;

            if (index == start) {
                cout << "Parking lot is full!\n";
                break;
            }
        }

        if (hashTable[index] == -1) {
            hashTable[index] = vehicle;
        }
    }

    cout << "\nFinal Parking Slots:\n";

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