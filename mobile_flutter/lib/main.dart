import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'data/datasources.dart';
import 'data/repositories_impl.dart';
import 'domain/repositories.dart';
import 'presentation/providers.dart';
import 'presentation/screens/auth_screens.dart';
import 'presentation/screens/main_screen.dart';

void main() {
  final apiClient = ApiClient();
  final remoteDataSource = RemoteDataSource(apiClient);
  
  final authRepo = AuthRepositoryImpl(remoteDataSource);
  final bookRepo = BookRepositoryImpl(remoteDataSource);
  final txRepo = BorrowRepositoryImpl(remoteDataSource);
  final categoryRepo = CategoryRepositoryImpl(remoteDataSource);
  final notificationRepo = NotificationRepositoryImpl(remoteDataSource);
  final fineRepo = FineRepositoryImpl(remoteDataSource);
  final reviewRepo = ReviewRepositoryImpl(remoteDataSource);

  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider(authRepo)),
        ChangeNotifierProvider(create: (_) => BookProvider(bookRepo)),
        ChangeNotifierProvider(create: (_) => CartProvider()),
        ChangeNotifierProvider(create: (_) => BorrowProvider(txRepo)),
        Provider<CategoryRepository>(create: (_) => categoryRepo),
        Provider<NotificationRepository>(create: (_) => notificationRepo),
        Provider<FineRepository>(create: (_) => fineRepo),
        Provider<ReviewRepository>(create: (_) => reviewRepo),
        ChangeNotifierProvider(create: (_) => ThemeProvider()),
      ],
      child: MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Consumer<ThemeProvider>(
      builder: (context, themeProvider, child) {
        return MaterialApp(
          title: 'Library App',
          themeMode: themeProvider.themeMode,
          theme: ThemeData(
            primarySwatch: Colors.blue,
            visualDensity: VisualDensity.adaptivePlatformDensity,
            brightness: Brightness.light,
          ),
          darkTheme: ThemeData(
            brightness: Brightness.dark,
            primarySwatch: Colors.blue,
            scaffoldBackgroundColor: Colors.grey[900],
            appBarTheme: AppBarTheme(backgroundColor: Colors.grey[900]),
            cardColor: Colors.grey[850],
          ),
          initialRoute: '/',
          routes: {
            '/': (context) => AuthChecker(),
            '/login': (context) => LoginScreen(),
            '/register': (context) => RegisterScreen(),
            '/main': (context) => MainScreen(),
          },
        );
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
