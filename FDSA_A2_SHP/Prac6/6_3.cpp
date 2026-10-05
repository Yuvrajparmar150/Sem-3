/*A calculator application receives arithmetic expressions typed by users in the usual way — numbers and operators written in between, 
with brackets to control priority (e.g. 3 + 4 * 2 or (3 + 4) * 2). Internally the calculator needs to convert each expression 
into a form where operators appear after their operands, so it can evaluate them without needing to re-read brackets. 
Given an infix expression, convert it to the equivalent postfix form and print the result.*/

#include <iostream>
#include <stack>
#include <string>
#include <cctype>
using namespace std;

int precedence(char op) {
    if (op == '+' || op == '-') return 1;
    if (op == '*' || op == '/') return 2;
    return 0;
}


string infixToPostfix(const string& expr) {
    stack<char> operators;
    string output;

    for (size_t i = 0; i < expr.length(); i++) {
        char token = expr[i];

        if (isspace(token)) continue;

      
        if (isdigit(token)) {
            output += token;
            output += ' ';
        }
     
        else if (token == '(') {
            operators.push(token);
        }
       
        else if (token == ')') {
            while (!operators.empty() && operators.top() != '(') {
                output += operators.top();
                output += ' ';
                operators.pop();
            }
            if (!operators.empty()) operators.pop(); // remove '('
        }
       
        else {
            while (!operators.empty() && precedence(operators.top()) >= precedence(token)) {
                output += operators.top();
                output += ' ';
                operators.pop();
            }
            operators.push(token);
        }
    }

    while (!operators.empty()) {
        output += operators.top();
        output += ' ';
        operators.pop();
    }

    return output;
}

int main() {
    string expr;
    cout << "Enter infix expression: ";
    getline(cin, expr);

    string postfix = infixToPostfix(expr);
    cout << "Postfix expression: " << postfix << endl;

    return 0;
}