import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:dio/dio.dart';
import '../../data/datasources.dart';
import '../providers.dart';

class EditProfileScreen extends StatefulWidget {
  @override
  _EditProfileScreenState createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  final _emailCtrl = TextEditingController();
  final _passwordCtrl = TextEditingController();
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    final user = Provider.of<AuthProvider>(context, listen: false).user;
    if (user != null) {
      _emailCtrl.text = user.email;
    }
  }

  Future<void> _updateProfile() async {
    setState(() => _isLoading = true);
    try {
      final dio = Dio(BaseOptions(baseUrl: 'http://10.0.2.2:8080/api'));
      final auth = Provider.of<AuthProvider>(context, listen: false);
      final token = await auth.getToken();
      if (token == null) throw Exception("Unauthorized");
      
      dio.options.headers['Authorization'] = 'Bearer $token';

      // Update email
      if (_emailCtrl.text.isNotEmpty) {
        await dio.put('/profile', data: {'email': _emailCtrl.text});
      }

      // Update password
      if (_passwordCtrl.text.isNotEmpty) {
        await dio.put('/profile/password', data: {'password': _passwordCtrl.text});
      }

      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Profile updated successfully')));
      Navigator.pop(context);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Failed to update profile: $e')));
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Edit Profile')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            TextField(
              controller: _emailCtrl,
              decoration: InputDecoration(labelText: 'Email', border: OutlineInputBorder()),
            ),
            SizedBox(height: 16),
            TextField(
              controller: _passwordCtrl,
              decoration: InputDecoration(labelText: 'New Password (leave blank to keep current)', border: OutlineInputBorder()),
              obscureText: true,
            ),
            SizedBox(height: 32),
            _isLoading
                ? CircularProgressIndicator()
                : FilledButton(
                    onPressed: _updateProfile,
                    child: Text('Save Changes'),
                  )
          ],
        ),
      ),
    );
  }
}
