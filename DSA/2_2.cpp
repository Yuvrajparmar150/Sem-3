// A librarian maintains a sorted catalog of book codes and needs to locate a specific code quickly. 
// Instead of going through every book, she opens the catalog to the middle, checks whether the 
// target is to the left or right, and repeats. Given a sorted list of book codes and a target code, 
// implement both approaches — one using a loop and one where the function calls itself — and 
// report the position of the target code

#include<iostream>
using namespace std;

int main(){
    int Book[6]={1,2,3,4,5,6};
 int m=6;
 int n=0;
 int X=5;
  while(n <= m){
    int mid = (n+m)/2;
    if(Book[mid]==X){
        cout<<"Book found at position:"<<mid+1;
        break;
    }else if(Book[mid]>X){
        m=mid-1;
    }else if(Book[mid]<X){
        n=mid+1;
    }
  }
}