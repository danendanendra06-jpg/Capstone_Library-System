import 'package:shared_preferences/shared_preferences.dart';
import '../domain/entities.dart';
import '../domain/repositories.dart';
import 'datasources.dart';

class AuthRepositoryImpl implements AuthRepository {
  final RemoteDataSource remoteDataSource;

  AuthRepositoryImpl(this.remoteDataSource);

  @override
  Future<String> login(String email, String password) async {
    final token = await remoteDataSource.login(email, password);
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);
    return token;
  }

  @override
  Future<void> register(String name, String email, String password) async {
    await remoteDataSource.register(name, email, password);
  }

  @override
  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
  }

  @override
  Future<User?> getProfile() async {
    try {
      return await remoteDataSource.getProfile();
    } catch (e) {
      return null;
    }
  }

  @override
  Future<bool> isLoggedIn() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.containsKey('token');
  }
}

class BookRepositoryImpl implements BookRepository {
  final RemoteDataSource remoteDataSource;

  BookRepositoryImpl(this.remoteDataSource);

  @override
  Future<List<Book>> getBooks() => remoteDataSource.getBooks();

  @override
  Future<List<Book>> searchBooks(String query) => remoteDataSource.searchBooks(query);

  @override
  Future<Book> getBookDetails(int id) => remoteDataSource.getBookDetails(id);
}

class TransactionRepositoryImpl implements TransactionRepository {
  final RemoteDataSource remoteDataSource;

  TransactionRepositoryImpl(this.remoteDataSource);

  @override
  Future<void> borrowBook(int bookId) => remoteDataSource.borrowBook(bookId);

  @override
  Future<List<Transaction>> getTransactions() => remoteDataSource.getTransactions();
}
