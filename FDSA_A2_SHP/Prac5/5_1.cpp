// A music player maintains a playlist where songs can be added to the beginning, added to the 
// end, or inserted right after a specific song that is currently playing. When a song is removed, 
// only the first song in the playlist is dropped. At any point the player can count how many songs 
// are in the playlist and display them from first to last. Given a sequence of such operations, 
// implement all of them on a doubly linked playlist and print the result after each operation.

#include <iostream>
#include <string>   
using namespace std;

struct Song {
    string title;
    Song* next;
    Song* prev;
};

   
void displayPlaylist(Song* head) {
    
    cout << "Playlist (First to Last): ";

    Song* temp = head;

    while (temp != nullptr) {
        cout << temp->title << " ";
        temp = temp->next;
    }

    cout << endl;
}

   
void addSongToBeginning(Song*& head, const string& title) { 
    
    Song* newSong = new Song{title, head, nullptr};

    if (head != nullptr) {
        head->prev = newSong;
    }

    head = newSong;

    displayPlaylist(head);
}

    
void addSongToEnd(Song*& head, const string& title) {
    
    Song* newSong = new Song{title, nullptr, nullptr};

    if (head == nullptr) {
        head = newSong;
    } else {
        Song* temp = head;

        while (temp->next != nullptr) {
            temp = temp->next;
        }

        temp->next = newSong;
        newSong->prev = temp;
    }

    displayPlaylist(head);
}

   
void insertSongAfter(Song*& head, const string& afterTitle, const string& newTitle) {   
    
    Song* temp = head;

    while (temp != nullptr && temp->title != afterTitle) {
        temp = temp->next;
    }

    if (temp == nullptr) {
        cout << "Song not found!" << endl;
        return;
    }

    Song* newSong = new Song{newTitle, temp->next, temp};

    if (temp->next != nullptr) {
        temp->next->prev = newSong;
    }

    temp->next = newSong;

    displayPlaylist(head);
}

void removeFirstSong(Song*& head) {
    
    if (head == nullptr) {
        cout << "Playlist is empty!" << endl;
        return;
    }

    Song* temp = head;

    head = head->next;

    if (head != nullptr) {
        head->prev = nullptr;
    }

    delete temp;

    displayPlaylist(head);
}


int countSongs(Song* head) {
    int count = 0;
    Song* temp = head;
    while (temp != nullptr) {
        count++;
        temp = temp->next;
    }
    return count;
}


int main() {
    
    Song* head = nullptr;

    int choice;
    string title, afterTitle;

    do {
        cout << "\n--- Music Player Playlist ---\n";
        cout << "1. Add Song to Beginning\n";
        cout << "2. Add Song to End\n";
        cout << "3. Insert Song After Specific Song\n";
        cout << "4. Remove First Song\n";
        cout << "5. Count Songs\n";
        cout << "6. Display Playlist\n";
        cout << "7. Exit\n";

        cout << "Enter your choice: ";
        cin >> choice;

        switch (choice) {
            case 1:
                cin.ignore();
                cout << "Enter song title: ";
                getline(cin, title);
                addSongToBeginning(head, title);
                break;

            case 2:
                cin.ignore();
                cout << "Enter song title: ";
                getline(cin, title);
                addSongToEnd(head, title);
                break;

            case 3:
                cin.ignore();
                cout << "Enter the title of the song after which to insert: ";
                getline(cin, afterTitle);
                cout << "Enter new song title: ";
                getline(cin, title);
                insertSongAfter(head, afterTitle, title);
                break;

            case 4:
                removeFirstSong(head);
                break;

            case 5:
                cout << "Number of songs in playlist: " << countSongs(head) << endl;
                break;

            case 6:
                displayPlaylist(head);
                break;

            case 7:
                cout << "Exiting program..." << endl;
                break;

            default:
                cout << "Invalid choice! Please try again." << endl;
        }
    } while (choice != 7);

    return 0;
}