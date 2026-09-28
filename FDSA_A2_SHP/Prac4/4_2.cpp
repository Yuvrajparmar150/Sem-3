// The hospital also needs to remove a patient token from the queue when a patient leaves, print 
// all remaining tokens from last to first for an end-of-day audit, and display the full queue from 
// front to back at any point. Implement deletion by value, reverse printing, and forward traversal 
// on the same queue from Problem 4.1a.
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

// Delete patient by value
void deleteByValue(Node*& head, const string& patient)
{
    if (head == nullptr)
    {
        cout << "Queue is empty!" << endl;
        return;
    }

    // If patient is at the front
    if (head->name == patient)
    {
        Node* temp = head;

        head = head->next;

        delete temp;

        cout << patient << " deleted." << endl;

        printQueue(head);

        return;
    }

    // Search for patient
    Node* temp = head;

    while (temp->next != nullptr &&
           temp->next->name != patient)
    {
        temp = temp->next;
    }

    // Patient not found
    if (temp->next == nullptr)
    {
        cout << "Patient not found!" << endl;
        return;
    }

    // Delete patient
    Node* deleteNode = temp->next;

    temp->next = deleteNode->next;

    delete deleteNode;

    cout << patient << " deleted." << endl;

    printQueue(head);
}

// Recursive function for reverse printing
void reversePrint(Node* head)
{
    if (head == nullptr)
    {
        return;
    }

    reversePrint(head->next);

    cout << head->name << " ";
}

// Display queue from back to front
void printReverse(Node* head)
{
    cout << "Queue (Back to Front): ";

    reversePrint(head);

    cout << endl;
}

int main()
{
    Node* head = nullptr;

    int choice;
    string name;

    do
    {
        cout << "\n--- Hospital Queue - Deletion & Traversal ---\n";
        cout << "1. Add Patient\n";
        cout << "2. Delete Patient by Name\n";
        cout << "3. Display Queue (Front to Back)\n";
        cout << "4. Display Queue (Back to Front)\n";
        cout << "5. Exit\n";

        cout << "Enter your choice: ";
        cin >> choice;

        switch (choice)
        {
            case 1:
                cin.ignore();

                cout << "Enter patient name: ";
                getline(cin, name);

                {
                    Node* newNode = new Node{name, nullptr};

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

                break;

            case 2:
                cin.ignore();

                cout << "Enter patient name to delete: ";
                getline(cin, name);

                deleteByValue(head, name);

                break;

            case 3:
                printQueue(head);
                break;

            case 4:
                printReverse(head);
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