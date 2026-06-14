import 'package:flutter/material.dart';
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
}

class BookProvider extends ChangeNotifier {
  final BookRepository repository;
  List<Book> books = [];
  List<Book> searchResults = [];
  bool isLoading = false;

  BookProvider(this.repository);

  Future<void> loadBooks() async {
    isLoading = true;
    notifyListeners();
    try {
      books = await repository.getBooks();
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

class TransactionProvider extends ChangeNotifier {
  final TransactionRepository repository;
  List<Transaction> transactions = [];
  bool isLoading = false;

  TransactionProvider(this.repository);

  Future<void> loadTransactions() async {
    isLoading = true;
    notifyListeners();
    try {
      transactions = await repository.getTransactions();
    } catch (e) {
      // Handle error
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> checkout(List<Book> cart) async {
    isLoading = true;
    notifyListeners();
    try {
      for (var book in cart) {
        await repository.borrowBook(book.id);
      }
      return true;
    } catch (e) {
      return false;
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }
}
