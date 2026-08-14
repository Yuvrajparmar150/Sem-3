// A librarian maintains a sorted catalog of book codes and needs to locate a specific code quickly. 
// Instead of going through every book, she opens the catalog to the middle, checks whether the 
// target is to the left or right, and repeats. Given a sorted list of book codes and a target code, 
// implement both approaches — one using a loop and one where the function calls itself — and 
// report the position of the target code

#include <iostream>
#include <vector>
using namespace std;

// Recursive binary search function
int binarySearchRecursive(const vector<int>& codes, int target, int left, int right) {
    if (left > right) {
        return -1;
    }

    int mid = left + (right - left) / 2;

    if (codes[mid] == target) {
        return mid; // found at mid
    }
    else if (codes[mid] < target) {
        // search in right half
        return binarySearchRecursive(codes, target, mid + 1, right);
    }
    else {
        // search in left half
        return binarySearchRecursive(codes, target, left, mid - 1);
    }
}         

int main() {
    // Sorted catalog of book codes
    vector<int> codes = {101, 203, 305, 407, 509, 612, 715};
    int target = 407;

    int position = binarySearchRecursive(codes, target, 0, codes.size() - 1);

    if (position != -1)
        cout << "Book code " << target << " found at position: " << position << endl;
    else
        cout << "Book code " << target << " not found in catalog." << endl;

    return 0;
}