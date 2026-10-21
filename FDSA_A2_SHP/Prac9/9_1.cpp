#include <iostream>
#include <vector>
#include <queue>
using namespace std;

// Depth First Search
void DFS(int node, vector<vector<int>>& adj, vector<bool>& visited) {
    visited[node] = true;
    cout << node << " ";

    for (int neighbor : adj[node]) {
        if (!visited[neighbor]) {
            DFS(neighbor, adj, visited);
        }
    }
}

// Breadth First Search
void BFS(int start, vector<vector<int>>& adj, int V) {
    vector<bool> visited(V, false);
    queue<int> q;

    visited[start] = true;
    q.push(start);

    while (!q.empty()) {
        int node = q.front();
        q.pop();

        cout << node << " ";

        for (int neighbor : adj[node]) {
            if (!visited[neighbor]) {
                visited[neighbor] = true;
                q.push(neighbor);
            }
        }
    }
}

int main() {
    int V, E;

    cout << "Enter number of buildings: ";
    cin >> V;

    cout << "Enter number of roads: ";
    cin >> E;

    vector<vector<int>> adj(V);

    cout << "Enter connected buildings (edges):\n";

    for (int i = 0; i < E; i++) {
        int u, v;
        cin >> u >> v;

        adj[u].push_back(v);
        adj[v].push_back(u);
    }

    int start;
    cout << "Enter starting building: ";
    cin >> start;

    vector<bool> visited(V, false);

    cout << "\nDFS Traversal: ";
    DFS(start, adj, visited);

    cout << "\nBFS Traversal: ";
    BFS(start, adj, V);

    return 0;
}