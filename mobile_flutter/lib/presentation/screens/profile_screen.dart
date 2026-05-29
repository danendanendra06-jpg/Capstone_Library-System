import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';
import 'transactions_screen.dart';

class ProfileScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    final user = auth.user;

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text('Profile', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        actions: [
          IconButton(
            icon: Icon(Icons.settings_outlined, color: Colors.black87),
            onPressed: () {},
          )
        ],
      ),
      body: user == null
          ? Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(24.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  CircleAvatar(
                    radius: 50,
                    backgroundColor: Theme.of(context).primaryColor.withOpacity(0.1),
                    child: Text(
                      user.name.isNotEmpty ? user.name[0].toUpperCase() : 'U',
                      style: TextStyle(fontSize: 40, fontWeight: FontWeight.bold, color: Theme.of(context).primaryColor),
                    ),
                  ),
                  SizedBox(height: 16),
                  Text(user.name, style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.black87)),
                  Text(user.email, style: TextStyle(fontSize: 16, color: Colors.grey[600])),
                  SizedBox(height: 32),
                  
                  Card(
                    elevation: 0,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16),
                      side: BorderSide(color: Colors.grey[200]!),
                    ),
                    child: Column(
                      children: [
                        ListTile(
                          leading: Container(
                            padding: EdgeInsets.all(8),
                            decoration: BoxDecoration(color: Colors.blue[50], borderRadius: BorderRadius.circular(8)),
                            child: Icon(Icons.history, color: Colors.blue),
                          ),
                          title: Text('Borrowing History', style: TextStyle(fontWeight: FontWeight.w600)),
                          trailing: Icon(Icons.chevron_right),
                          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => TransactionsScreen())),
                        ),
                        Divider(height: 1, indent: 16, endIndent: 16),
                        ListTile(
                          leading: Container(
                            padding: EdgeInsets.all(8),
                            decoration: BoxDecoration(color: Colors.purple[50], borderRadius: BorderRadius.circular(8)),
                            child: Icon(Icons.bookmark_outline, color: Colors.purple),
                          ),
                          title: Text('Saved Books', style: TextStyle(fontWeight: FontWeight.w600)),
                          trailing: Icon(Icons.chevron_right),
                          onTap: () {},
                        ),
                      ],
                    ),
                  ),
                  
                  SizedBox(height: 32),
                  SizedBox(
                    width: double.infinity,
                    child: OutlinedButton.icon(
                      style: OutlinedButton.styleFrom(
                        padding: EdgeInsets.symmetric(vertical: 16),
                        side: BorderSide(color: Colors.red[300]!),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                      icon: Icon(Icons.logout, color: Colors.red),
                      label: Text('Sign Out', style: TextStyle(color: Colors.red, fontSize: 16, fontWeight: FontWeight.bold)),
                      onPressed: () async {
                        await auth.logout();
                        Navigator.pushReplacementNamed(context, '/login');
                      },
                    ),
                  )
                ],
              ),
            ),
    );
  }
}
