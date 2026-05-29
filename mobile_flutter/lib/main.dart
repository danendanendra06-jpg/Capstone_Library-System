import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'data/datasources.dart';
import 'data/repositories_impl.dart';
import 'presentation/providers.dart';
import 'presentation/screens/auth_screens.dart';
import 'presentation/screens/main_screen.dart';

void main() {
  final apiClient = ApiClient();
  final remoteDataSource = RemoteDataSource(apiClient);
  
  final authRepo = AuthRepositoryImpl(remoteDataSource);
  final bookRepo = BookRepositoryImpl(remoteDataSource);
  final txRepo = TransactionRepositoryImpl(remoteDataSource);

  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider(authRepo)),
        ChangeNotifierProvider(create: (_) => BookProvider(bookRepo)),
        ChangeNotifierProvider(create: (_) => CartProvider()),
        ChangeNotifierProvider(create: (_) => TransactionProvider(txRepo)),
      ],
      child: MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Library App',
      theme: ThemeData(
        primarySwatch: Colors.blue,
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
      initialRoute: '/',
      routes: {
        '/': (context) => AuthChecker(),
        '/login': (context) => LoginScreen(),
        '/register': (context) => RegisterScreen(),
        '/main': (context) => MainScreen(),
      },
    );
  }
}

class AuthChecker extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Consumer<AuthProvider>(
      builder: (context, auth, child) {
        if (auth.isAuthenticated) {
          return MainScreen();
        } else {
          return LoginScreen();
        }
      },
    );
  }
}
