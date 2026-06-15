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
  Future<List<Book>> getPopularBooks();
  Future<List<Book>> getBooksByCategory(int categoryId);
  Future<List<Book>> searchBooks(String query);
  Future<Book> getBookDetails(int id);
}

abstract class BorrowRepository {
  Future<void> borrowBook(int bookId, {String? dueDate});
  Future<List<Borrow>> getBorrows();
}

abstract class CategoryRepository {
  Future<List<Category>> getCategories();
}

abstract class NotificationRepository {
  Future<List<Notification>> getNotifications();
}

abstract class FineRepository {
  Future<List<Fine>> getFines();
  Future<void> payFine(int id, String method);
}

abstract class ReviewRepository {
  Future<List<Review>> getReviews(int bookId);
  Future<void> submitReview(int bookId, int rating, String comment);
}
