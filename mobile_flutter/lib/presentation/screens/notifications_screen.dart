import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../domain/repositories.dart';
import '../../domain/entities.dart' as entity; // Alias to avoid collision with Flutter's Notification
import 'fines_screen.dart';

class NotificationsScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final repo = Provider.of<NotificationRepository>(context, listen: false);

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text('Notifications', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        backgroundColor: Colors.white,
        elevation: 1,
        iconTheme: IconThemeData(color: Colors.black87),
      ),
      body: FutureBuilder<List<entity.Notification>>(
        future: repo.getNotifications(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) return Center(child: CircularProgressIndicator());
          if (!snapshot.hasData || snapshot.data!.isEmpty) return Center(child: Text('No new notifications.', style: TextStyle(color: Colors.grey[600], fontSize: 16)));
          
          return ListView.builder(
            padding: EdgeInsets.all(16),
            itemCount: snapshot.data!.length,
            itemBuilder: (context, index) {
              final notif = snapshot.data![index];
              return Card(
                elevation: 1,
                margin: EdgeInsets.only(bottom: 12),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                color: notif.isRead ? Colors.white : Colors.blue[50],
                child: ListTile(
                  leading: CircleAvatar(
                    backgroundColor: notif.isRead ? Colors.grey[200] : Colors.blue[100],
                    child: Icon(Icons.notifications, color: notif.isRead ? Colors.grey[600] : Colors.blue[700]),
                  ),
                  title: Text(notif.title, style: TextStyle(fontWeight: notif.isRead ? FontWeight.normal : FontWeight.bold)),
                  subtitle: Text(notif.message),
                  trailing: Text(notif.sentAt.split('T')[0], style: TextStyle(fontSize: 12, color: Colors.grey)),
                  onTap: () {
                    // Navigate to fines screen if the notification is about fines
                    if (notif.message.toLowerCase().contains('fine') || notif.message.toLowerCase().contains('pembayaran') || notif.message.toLowerCase().contains('denda')) {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => FinesScreen()));
                    }
                  },
                ),
              );
            },
          );
        },
      ),
    );
  }
}
