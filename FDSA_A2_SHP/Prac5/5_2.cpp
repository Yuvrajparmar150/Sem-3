// A group of students are sitting in a circle playing a passing game. A token starts at the first 
// student and is passed around the circle one student at a time. Students can join the circle at any 
// position and leave at any time, and the circle must remain unbroken after every join or leave. 
// Given a sequence of join, leave, and display operations, implement them on both a singly 
// circular and a doubly circular linked list and print the current circle after each operation
#include <iostream>
#include <string>
using namespace std;
struct Node
{
    string name;
    Node* next;
    Node* prev;
};

int countStudents(Node* head) {
    if (head == nullptr) return 0;

    int count = 0;
    Node* current = head;
    do {
        count++;
        current = current->next;
    } while (current != head);

    return count;
}

void addStudentToBeginning(Node*& head, const string& name) {
    Node* newNode = new Node{ name, nullptr, nullptr };

    if (head == nullptr) {
        newNode->next = newNode;
        newNode->prev = newNode;
        head = newNode;
        cout << "Student '" << name << "' added to the circle." << endl;
        return;
    }

    Node* tail = head->prev;
    newNode->next = head;
    newNode->prev = tail;
    head->prev = newNode;
    tail->next = newNode;
    head = newNode;

    cout << "Student '" << name << "' added to the beginning." << endl;
}

void addStudentToEnd(Node*& head, const string& name) {
    Node* newNode = new Node{ name, nullptr, nullptr };

    if (head == nullptr) {
        newNode->next = newNode;
        newNode->prev = newNode;
        head = newNode;
        cout << "Student '" << name << "' added to the circle." << endl;
        return;
    }

    Node* tail = head->prev;
    newNode->next = head;
    newNode->prev = tail;
    tail->next = newNode;
    head->prev = newNode;

    cout << "Student '" << name << "' added to the end." << endl;
}

void insertStudentAfter(Node*& head, const string& afterName, const string& newName) {
    if (head == nullptr) {
        cout << "Circle is empty. Cannot insert." << endl;
        return;
    }

    Node* current = head;
    do {
        if (current->name == afterName) {
            Node* newNode = new Node{ newName, nullptr, nullptr };
            newNode->next = current->next;
            newNode->prev = current;
            current->next->prev = newNode;
            current->next = newNode;

            cout << "Student '" << newName << "' inserted after '" << afterName << "'." << endl;
            return;
        }
        current = current->next;
    } while (current != head);

    cout << "Student '" << afterName << "' not found in the circle." << endl;
}

void removeFirstStudent(Node*& head) {
    if (head == nullptr) {
        cout << "Circle is empty. Nothing to remove." << endl;
        return;
    }

    if (head->next == head) {
        cout << "Student '" << head->name << "' removed from the circle." << endl;
        delete head;
        head = nullptr;
        return;
    }

    Node* temp = head;
    Node* tail = head->prev;

    head = head->next;
    head->prev = tail;
    tail->next = head;

    cout << "Student '" << temp->name << "' removed from the front." << endl;
    delete temp;
}

void displayCircle(Node* head) {
    if (head == nullptr) {
        cout << "Circle is empty." << endl;
        return;
    }

    Node* current = head;
    cout << "Current circle: ";
    do {
        cout << current->name;
        current = current->next;
        if (current != head) {
            cout << " -> ";
        }
    } while (current != head);
    cout << endl;
}

int main(){
Node* head = nullptr;

    int choice;
    string name, afterName;

    do {
        cout << "\n--- Passing Game Circle ---\n";
        cout << "1. Add Student to Beginning\n";
        cout << "2. Add Student to End\n";
        cout << "3. Insert Student After Specific Student\n";
        cout << "4. Remove First Student\n";
        cout << "5. Count Students\n";
        cout << "6. Display Circle\n";
        cout << "7. Exit\n";

        cout << "Enter your choice: ";
        cin >> choice;

        switch (choice) {
            case 1:
                cin.ignore();
                cout << "Enter student name: ";
                getline(cin, name);
                addStudentToBeginning(head, name);
                break;

            case 2:
                cin.ignore();
                cout << "Enter student name: ";
                getline(cin, name);
                addStudentToEnd(head, name);
                break;

            case 3:
                cin.ignore();
                cout << "Enter the name of the student after which to insert: ";
                getline(cin, afterName);
                cout << "Enter new student name: ";
                getline(cin, name);
                insertStudentAfter(head, afterName, name);
                break;

            case 4:
                removeFirstStudent(head);
                break;

            case 5:
                cout << "Number of students in circle: " << countStudents(head) << endl;
                break;

            case 6:
                displayCircle(head);
                break;

            case 7:
                cout << "Exiting program..." << endl;
                break;

            default:
                cout << "Invalid choice. Please try again." << endl;
                break;
        }
    } while (choice != 7);
}