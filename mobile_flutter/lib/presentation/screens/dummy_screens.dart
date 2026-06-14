import 'package:flutter/material.dart';

class SettingsScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Settings', style: TextStyle(color: Colors.black87)), backgroundColor: Colors.white, iconTheme: IconThemeData(color: Colors.black87), elevation: 1),
      body: Center(child: Text('Settings Options Coming Soon')),
    );
  }
}

class CategoriesScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Categories', style: TextStyle(color: Colors.black87)), backgroundColor: Colors.white, iconTheme: IconThemeData(color: Colors.black87), elevation: 1),
      body: Center(child: Text('Categories Coming Soon')),
    );
  }
}

class SavedScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Saved / Favourites', style: TextStyle(color: Colors.black87)), backgroundColor: Colors.white, iconTheme: IconThemeData(color: Colors.black87), elevation: 1),
      body: Center(child: Text('No saved books yet')),
    );
  }
}
