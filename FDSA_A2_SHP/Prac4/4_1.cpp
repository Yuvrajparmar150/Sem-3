// A hospital manages a queue of patient tokens. New critical patients must be added to the front, 
// routine patients are added to the end, and occasionally a patient with a priority number must be 
// inserted at a specific position in the queue. Given a sequence of such operations, implement all 
// three insertion types and print the final queue after each operation

#include <iostream>
#include <deque>
using namespace std;

int main() {
    deque<int> patients;

    int n;
    cout << "Enter number of operations: ";
    cin >> n;

    for (int i = 0; i < n; i++) {
        int type, token;

        cout << "\nEnter operation type (1-Front, 2-End, 3-Position): ";
        cin >> type;

        if (type == 1) {
            cout << "Enter patient token: ";
            cin >> token;

            patients.push_front(token);
        }
        else if (type == 2) {
            // Add routine patient at end
            cout << "Enter patient token: ";
            cin >> token;

            patients.push_back(token);
        }
        else if (type == 3) {

            int position;

            cout << "Enter patient token: ";
            cin >> token;

            cout << "Enter position: ";
            cin >> position;

            if (position >= 0 && position <= patients.size()) {
                patients.insert(patients.begin() + position, token);
            }
            else {
                cout << "Invalid position!" << endl;
                continue;
            }
        }
        else {
            cout << "Invalid operation!" << endl;
            continue;
        }

        cout << "Current Queue: ";

        for (int patient : patients) {
            cout << patient << " ";
        }

        cout << endl;
    }

    return 0;
}