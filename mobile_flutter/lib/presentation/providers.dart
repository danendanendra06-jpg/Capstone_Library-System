import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../domain/entities.dart';
import '../domain/repositories.dart';

class AuthProvider extends ChangeNotifier {
  final AuthRepository repository;
  bool isAuthenticated = false;
  User? user;
  bool isLoading = false;

  AuthProvider(this.repository) {
    checkAuthStatus();
  }

  Future<void> checkAuthStatus() async {
    isAuthenticated = await repository.isLoggedIn();
    if (isAuthenticated) {
      user = await repository.getProfile();
      if (user == null) {
        isAuthenticated = false;
        await logout(); // Clear stale token
      }
    }
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    isLoading = true;
    notifyListeners();
    try {
      await repository.login(email, password);
      await checkAuthStatus();
      return true;
    } catch (e) {
      return false;
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> register(String name, String email, String password) async {
    isLoading = true;
    notifyListeners();
    try {
      await repository.register(name, email, password);
      return true;
    } catch (e) {
      print('Registration Error: $e');
      return false;
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  Future<void> logout() async {
    await repository.logout();
    isAuthenticated = false;
    user = null;
    notifyListeners();
  }

  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }
}

class ThemeProvider extends ChangeNotifier {
  ThemeMode themeMode = ThemeMode.system;

  ThemeProvider() {
    _loadTheme();
  }

  Future<void> _loadTheme() async {
    final prefs = await SharedPreferences.getInstance();
    final isDark = prefs.getBool('isDark') ?? false;
    themeMode = isDark ? ThemeMode.dark : ThemeMode.light;
    notifyListeners();
  }

  Future<void> toggleTheme(bool isDark) async {
    themeMode = isDark ? ThemeMode.dark : ThemeMode.light;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('isDark', isDark);
    notifyListeners();
  }

  bool get isDarkMode => themeMode == ThemeMode.dark;
}
class BookProvider extends ChangeNotifier {
  final BookRepository repository;
  List<Book> books = []; // Let's use this as latestBooks
  List<Book> popularBooks = [];
  List<Book> searchResults = [];
  bool isLoading = false;

  BookProvider(this.repository);

  Future<void> loadBooks({int? categoryId}) async {
    isLoading = true;
    notifyListeners();
    try {
      if (categoryId != null) {
        books = await repository.getBooksByCategory(categoryId);
      } else {
        books = await repository.getBooks();
      }
    } catch (e) {
      // Handle error
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  Future<void> loadPopularBooks() async {
    isLoading = true;
    notifyListeners();
    try {
      popularBooks = await repository.getPopularBooks();
    } catch (e) {
      // Handle error
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  Future<void> searchBooks(String query) async {
    isLoading = true;
    notifyListeners();
    try {
      searchResults = await repository.searchBooks(query);
    } catch (e) {
      // Handle error
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }
}

class CartProvider extends ChangeNotifier {
  final List<Book> _cart = [];
  List<Book> get cart => _cart;

  void addToCart(Book book) {
    if (!_cart.any((b) => b.id == book.id)) {
      _cart.add(book);
      notifyListeners();
    }
  }

  void removeFromCart(Book book) {
    _cart.removeWhere((b) => b.id == book.id);
    notifyListeners();
  }

  void clearCart() {
    _cart.clear();
    notifyListeners();
  }
}

class BorrowProvider extends ChangeNotifier {
  final BorrowRepository repository;
  List<Borrow> borrows = [];
  bool isLoading = false;

  BorrowProvider(this.repository);

  Future<void> loadBorrows() async {
    isLoading = true;
    notifyListeners();
    try {
      borrows = await repository.getBorrows();
    } catch (e) {
      // Handle error
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  Future<String?> checkout(List<Book> cart, {String? dueDate}) async {
    isLoading = true;
    notifyListeners();
    try {
      for (var book in cart) {
        await repository.borrowBook(book.id, dueDate: dueDate);
      }
      return null;
    } catch (e) {
      if (e is DioException && e.response?.data != null && e.response?.data['message'] != null) {
        return e.response?.data['message'];
      }
      return e.toString();
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }
}
