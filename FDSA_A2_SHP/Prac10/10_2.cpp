#include <iostream>
#include <vector>
using namespace std;

#define SIZE 10

int main() {
    vector<int> hashTable[SIZE];

    int n, book;

    cout << "Enter number of books: ";
    cin >> n;

    for (int i = 0; i < n; i++) {
        cout << "Enter book code: ";
        cin >> book;

        int index = (book % SIZE + SIZE) % SIZE;

        hashTable[index].push_back(book);
    }

    cout << "\nFinal Library Shelves:\n";

    for (int i = 0; i < SIZE; i++) {
        cout << "Shelf " << i << ": ";

        if (hashTable[i].empty()) {
            cout << "Empty";
        }
        else {
            for (int book : hashTable[i]) {
                cout << book << " ";
            }
        }

        cout << endl;
    }

    return 0;
}