// A hospital manages a queue of patient tokens. New critical patients must be added to the front, 
// routine patients are added to the end, and occasionally a patient with a priority number must be 
// inserted at a specific position in the queue. Given a sequence of such operations, implement all 
// three insertion types and print the final queue after each operation

#include <iostream>
#include <string>
using namespace std;

struct Node
{
    string name;
    Node* next;
};

// Display queue from front to back
void printQueue(Node* head)
{
    cout << "Queue (Front to Back): ";

    Node* temp = head;

    while (temp != nullptr)
    {
        cout << temp->name << " ";
        temp = temp->next;
    }

    cout << endl;
}

// Add critical patient at the front
void addCritical(Node*& head, const string& patient)
{
    Node* newNode = new Node{patient, head};

    head = newNode;

    printQueue(head);
}

// Add routine patient at the end
void addRoutine(Node*& head, const string& patient)
{
    Node* newNode = new Node{patient, nullptr};

    if (head == nullptr)
    {
        head = newNode;
    }
    else
    {
        Node* temp = head;

        while (temp->next != nullptr)
        {
            temp = temp->next;
        }

        temp->next = newNode;
    }

    printQueue(head);
}

// Add priority patient at a specific position
void addPriority(Node*& head, const string& patient, int position)
{
    Node* newNode = new Node{patient, nullptr};

    // Position 0 means front
    if (position == 0)
    {
        newNode->next = head;
        head = newNode;

        printQueue(head);
        return;
    }

    Node* temp = head;
    int index = 0;

    // Move to node just before required position
    while (temp != nullptr && index < position - 1)
    {
        temp = temp->next;
        index++;
    }

    // Position is greater than queue length
    if (temp == nullptr)
    {
        cout << "Invalid position!" << endl;

        delete newNode;
        return;
    }

    // Insert new node
    newNode->next = temp->next;
    temp->next = newNode;

    printQueue(head);
}

int main()
{
    Node* head = nullptr;

    int choice;
    int position;
    string name;

    do
    {
        cout << "\n--- Hospital Queue - Insertion ---\n";
        cout << "1. Add Critical Patient (Front)\n";
        cout << "2. Add Routine Patient (End)\n";
        cout << "3. Add Priority Patient (Specific Position)\n";
        cout << "4. Display Queue\n";
        cout << "5. Exit\n";

        cout << "Enter your choice: ";
        cin >> choice;

        switch (choice)
        {
            case 1:
                cin.ignore();

                cout << "Enter patient name: ";
                getline(cin, name);

                addCritical(head, name);
                break;

            case 2:
                cin.ignore();

                cout << "Enter patient name: ";
                getline(cin, name);

                addRoutine(head, name);
                break;

            case 3:
                cin.ignore();

                cout << "Enter patient name: ";
                getline(cin, name);

                cout << "Enter position (0 = front): ";
                cin >> position;

                addPriority(head, name, position);
                break;

            case 4:
                printQueue(head);
                break;

            case 5:
                cout << "Exiting program..." << endl;
                break;

            default:
                cout << "Invalid choice!" << endl;
        }

    } while (choice != 5);

    return 0;
}