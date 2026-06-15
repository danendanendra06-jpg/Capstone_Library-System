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
  Future<List<Book>> getPopularBooks() => remoteDataSource.getPopularBooks();

  @override
  Future<List<Book>> getBooksByCategory(int categoryId) => remoteDataSource.getBooksByCategory(categoryId);

  @override
  Future<List<Book>> searchBooks(String query) => remoteDataSource.searchBooks(query);

  @override
  Future<Book> getBookDetails(int id) => remoteDataSource.getBookDetails(id);
}

class BorrowRepositoryImpl implements BorrowRepository {
  final RemoteDataSource remoteDataSource;

  BorrowRepositoryImpl(this.remoteDataSource);

  @override
  Future<void> borrowBook(int bookId, {String? dueDate}) => remoteDataSource.borrowBook(bookId, dueDate: dueDate);

  @override
  Future<List<Borrow>> getBorrows() => remoteDataSource.getBorrows();
}

class CategoryRepositoryImpl implements CategoryRepository {
  final RemoteDataSource remoteDataSource;
  CategoryRepositoryImpl(this.remoteDataSource);
  @override
  Future<List<Category>> getCategories() => remoteDataSource.getCategories();
}

class NotificationRepositoryImpl implements NotificationRepository {
  final RemoteDataSource remoteDataSource;
  NotificationRepositoryImpl(this.remoteDataSource);
  @override
  Future<List<Notification>> getNotifications() => remoteDataSource.getNotifications();
}

class FineRepositoryImpl implements FineRepository {
  final RemoteDataSource remoteDataSource;
  FineRepositoryImpl(this.remoteDataSource);
  @override
  Future<List<Fine>> getFines() => remoteDataSource.getFines();
  @override
  Future<bool> payFine(int id, String method, double amount) => remoteDataSource.payFine(id, method, amount);
}

class ReviewRepositoryImpl implements ReviewRepository {
  final RemoteDataSource remoteDataSource;
  ReviewRepositoryImpl(this.remoteDataSource);
  @override
  Future<List<Review>> getReviews(int bookId) => remoteDataSource.getReviews(bookId);
  @override
  Future<void> submitReview(int bookId, int rating, String comment) => remoteDataSource.submitReview(bookId, rating, comment);
}
