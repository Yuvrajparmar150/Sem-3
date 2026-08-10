// A paint shop has buckets labeled with one of three colour codes — 0, 1, or 2 — but they are 
// stored in a random order. The shop owner wants all 0s together, then all 1s, then all 2s, without 
// using any extra storage. Given the list of colour codes, rearrange them in place and print the 
// result
#include<iostream>
#include<vector>

using namespace std;

void bubbleSort(vector<int>& arr)
{
    int n=arr.size();
    for(int i=1;i<n-1;i++)
    {
        for(int j=0; j< n-i-1; j++){
            if(arr[j]>arr[j+1]){
                swap(arr[j],arr[j+1]);
            }
        }
      
    }
}
int main()
{
    vector<int> colors={2,0,1,2,1,0,0,2,1,0,2};
    bubbleSort(colors);
    cout<<"Sorted Colors:";
    for(int i=0;i<colors.size();i++)
    {
        cout<<colors[i]<<" ";
    }
    cout<<endl;
    return 0;
}