// A school newspaper editor wants to highlight the most impressive word from a submitted article 
// on the front page. The word is chosen simply by length — the longest one wins. Given a 
// sentence, print the winning word and how many letters it has.
// Describe the approach you used. What does your solution do when two words have the same 
// length? Is that behavior intentional, and can you think of a way to handle it explicitly?

#include <iostream>
#include <string>

using namespace std;

int main(){
    string sentence;
    cout << "Enter a sentence: ";
    getline(cin, sentence);

    string longestWord;
    int maxLength = 0;

    string currentWord;
    for (char c : sentence){
        if (c != ' '&& c != '.' && c != ',' && c != '!' && c != '?' && c != ';' && c != ':'){
            currentWord += c;
        }else{
            if (currentWord.length() > maxLength){
                maxLength = currentWord.length();
                longestWord = currentWord;
            }
              currentWord.clear();
        } 
    }
    if (currentWord.length() > maxLength){
        maxLength = currentWord.length();
        longestWord = currentWord;
    }
   

    cout << "The longest word is: " << longestWord << " with length: " << maxLength << endl;

    return 0;
}
