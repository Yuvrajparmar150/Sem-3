// A web browser keeps track of pages visited so that the back button always returns to the most 
// recently visited page. Unlike a fixed counter, the browser has no hard limit on how many pages 
// it can remember — it grows as the user visits more pages and shrinks as they press back. 
// Given a sequence of visit and back operations, implement this unlimited page history and print 
// the current page after each operation.

#include <iostream>
#include <string>
#include <stack>

using namespace std;

class BrowserHistory {
private:
    stack<string> history;

public:
    BrowserHistory(string homepage) {
        history.push(homepage);
        cout << history.top() << endl;
    }

    void visit(string page) {
        history.push(page);
        cout << history.top() << endl;
    }

    void back() {
        if (history.size() > 1) {
            history.pop();
            cout << history.top() << endl;
        } else {
            cout << "Error: Already at homepage" << endl;
        }
    }
};

int main() {
    BrowserHistory browser("google.com");

    browser.visit("github.com");
    browser.visit("wikipedia.org");
    browser.back();
    browser.visit("stackoverflow.com");
    browser.back();
    browser.back();
    browser.back();

    return 0;
}