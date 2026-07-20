// A security guard at a parking lot checks vehicles one by one from the entrance to find a car with 
// a specific license plate. Sometimes he starts from the entrance, sometimes he calls a helper 
// who starts from where the guard left off. Given a list of license plates and a target plate, 
// implement both approaches — one that checks plates one by one from the start, and one where 
// the function calls itself to continue checking — and report the position of the target plate if 
// found.
#include<iostream>
using namespace std;

int main(){
    int Car[5];
    int X;
int Carfound=0;
 for(int i=0;i<5;i++){
    cout<<"Enter Licence plate no. for car "<<i+1<<":";
    cin>>Car[i];
}

cout<<"Enter a car licence plate no. to search:";
cin>>X;

for(int j=0;j<5;j++){
    if(Car[j]==X){
        Carfound+=1;
        cout<<"Car " << Car[j]<< " found at postion: "<<j+1;
    }
}
if(Carfound == 0){
    cout<<"car not Found!!"<<endl;
}

return 0;
}