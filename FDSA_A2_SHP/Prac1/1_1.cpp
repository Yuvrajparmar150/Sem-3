// A bakery prepares n items every morning and places them in a display row. At the end of each
// hour, the leftmost item is moved to the rightmost position to make room for fresh stock at the
// front. Given the initial row and the number of hours h, print the final display order.
// Describe the approach you used. How does it behave when h is very large, say 10 million? Is
// there a way to get the correct result without performing the operation h times?

#include<iostream>
using namespace std;

int main(){
    int n,h;
    int arr[10];

    cout<<"Enter the items in the Display row: ";

    for(int i=0;i<10;i++){
        cin>>arr[i];
    }
    cout<<"Enter no. of hours:";
    cin>>h;
    n=h%10;
    for(int i=0;i<n;i++){
        int temp=arr[0];
        for(int j=0;j<9;j++){
            arr[j]=arr[j+1];
     }
        arr[9]=temp;
    }

    cout<<"Final Display order after "<<h<<" hours is: ";
    for(int i=0;i<10;i++){
        cout<<arr[i]<<" ";
    }   
}