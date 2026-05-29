import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailCtrl = TextEditingController();
  final _passwordCtrl = TextEditingController();

  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            TextField(controller: _emailCtrl, decoration: InputDecoration(labelText: 'Email')),
            SizedBox(height: 16),
            TextField(controller: _passwordCtrl, decoration: InputDecoration(labelText: 'Password'), obscureText: true),
            SizedBox(height: 32),
            auth.isLoading
                ? CircularProgressIndicator()
                : ElevatedButton(
                    onPressed: () async {
                      if (await auth.login(_emailCtrl.text, _passwordCtrl.text)) {
                        Navigator.pushReplacementNamed(context, '/main');
                      } else {
                        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Login failed')));
                      }
                    },
                    child: Text('Login'),
                  ),
            TextButton(
              onPressed: () => Navigator.pushNamed(context, '/register'),
              child: Text('Register'),
            )
          ],
        ),
      ),
    );
  }
}

class RegisterScreen extends StatefulWidget {
  @override
  _RegisterScreenState createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _nameCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _passwordCtrl = TextEditingController();

  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    return Scaffold(
      appBar: AppBar(title: Text('Register')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            TextField(controller: _nameCtrl, decoration: InputDecoration(labelText: 'Name')),
            SizedBox(height: 16),
            TextField(controller: _emailCtrl, decoration: InputDecoration(labelText: 'Email')),
            SizedBox(height: 16),
            TextField(controller: _passwordCtrl, decoration: InputDecoration(labelText: 'Password'), obscureText: true),
            SizedBox(height: 32),
            auth.isLoading
                ? CircularProgressIndicator()
                : ElevatedButton(
                    onPressed: () async {
                      if (await auth.register(_nameCtrl.text, _emailCtrl.text, _passwordCtrl.text)) {
                        Navigator.pushReplacementNamed(context, '/main');
                      } else {
                        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Registration failed')));
                      }
                    },
                    child: Text('Register'),
                  ),
          ],
        ),
      ),
    );
  }
}
