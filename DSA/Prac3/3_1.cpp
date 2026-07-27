// A teacher has a stack of student answer sheets with marks written on them and needs to 
// arrange them in order before entering grades. She tries three different methods: in the first, she 
// repeatedly compares adjacent sheets and swaps them if they are out of order; in the second, 
// she finds the lowest-marked sheet each time and places it at the front; in the third, she picks 
// each sheet one by one and inserts it into its correct position among the already-arranged 
// sheets. Implement all three methods and for each one, print the sorted order of marks
//ar[]=85137

#include<iostream>
#include<vector>

using namespace std;
void selectionSort(vector<int> &arr){
     int n = arr.size();
     for(int i=0; i<n-1; i++){
        int min=i;
        for(int j=i+1; j<n; j++){
            if(arr[min]>arr[j]){
            j=min;
            }
        }
        swap(arr[i],arr[min]);
     }
}
void bubbleSort(vector<int> &arr){
  int n= arr.size();
  for(int i=0 ; i<n-1 ; i++){
    for(int j=0 ; j<n-i-1 ; j++){
        if(arr[j]>arr[j+1]){
            swap(arr[j],arr[j+1]);
        }
    }
  }

}
int main(){
    vector<int> marks={8,5,1,3,7};
vector<int> bubble = marks;
    bubbleSort(bubble);

vector<int> selection = marks;
    selectionSort(selection);
    
    }