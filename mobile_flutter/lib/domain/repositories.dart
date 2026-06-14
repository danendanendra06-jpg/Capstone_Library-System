import 'entities.dart';

abstract class AuthRepository {
  Future<String> login(String email, String password);
  Future<void> register(String name, String email, String password);
  Future<void> logout();
  Future<User?> getProfile();
  Future<bool> isLoggedIn();
}

abstract class BookRepository {
  Future<List<Book>> getBooks();
  Future<List<Book>> searchBooks(String query);
  Future<Book> getBookDetails(int id);
}

abstract class TransactionRepository {
  Future<void> borrowBook(int bookId);
  Future<List<Transaction>> getTransactions();
}
