import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../core/constants.dart';
import 'models.dart';

class ApiClient {
  final Dio dio;
  
  ApiClient() : dio = Dio(BaseOptions(baseUrl: ApiConstants.baseUrl)) {
    dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final prefs = await SharedPreferences.getInstance();
        final token = prefs.getString('token');
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        return handler.next(options);
      },
    ));
  }
}

class RemoteDataSource {
  final ApiClient apiClient;

  RemoteDataSource(this.apiClient);

  Future<String> login(String email, String password) async {
    final response = await apiClient.dio.post(ApiConstants.login, data: {
      'username': email,
      'password': password,
    });
    return response.data['token'];
  }

  Future<void> register(String name, String email, String password) async {
    await apiClient.dio.post(ApiConstants.register, data: {
      'username': name, // Fix: send full name as username
      'email': email,
      'password': password,
    });
  }

  Future<UserModel> getProfile() async {
    final response = await apiClient.dio.get(ApiConstants.profile);
    return UserModel.fromJson(response.data);
  }

  Future<List<BookModel>> getBooks() async {
    final response = await apiClient.dio.get(ApiConstants.books);
    final data = response.data is Map ? response.data['content'] : response.data;
    return (data as List).map((x) => BookModel.fromJson(x)).toList();
  }

  Future<List<BookModel>> getPopularBooks() async {
    final response = await apiClient.dio.get(ApiConstants.books, queryParameters: {'sortCustom': 'popular'});
    final data = response.data is Map ? response.data['content'] : response.data;
    return (data as List).map((x) => BookModel.fromJson(x)).toList();
  }

  Future<List<BookModel>> searchBooks(String query) async {
    final response = await apiClient.dio.get(ApiConstants.books, queryParameters: {'title': query});
    final data = response.data is Map ? response.data['content'] : response.data;
    return (data as List).map((x) => BookModel.fromJson(x)).toList();
  }

  Future<BookModel> getBookDetails(int id) async {
    final response = await apiClient.dio.get('${ApiConstants.books}/$id');
    return BookModel.fromJson(response.data);
  }

  Future<void> borrowBook(int bookId, {String? dueDate}) async {
    final payload = <String, dynamic>{'bookId': bookId};
    if (dueDate != null) {
      payload['dueDate'] = dueDate;
    }
    await apiClient.dio.post(ApiConstants.borrow, data: payload);
  }



  Future<List<BorrowModel>> getBorrows() async {
    final response = await apiClient.dio.get(ApiConstants.borrows);
    return (response.data as List).map((x) => BorrowModel.fromJson(x)).toList();
  }

  Future<List<CategoryModel>> getCategories() async {
    final response = await apiClient.dio.get(ApiConstants.categories);
    final data = response.data is Map ? response.data['content'] : response.data;
    return (data as List).map((x) => CategoryModel.fromJson(x)).toList();
  }

  Future<List<BookModel>> getBooksByCategory(int categoryId) async {
    final response = await apiClient.dio.get(ApiConstants.books, queryParameters: {'categoryId': categoryId});
    final data = response.data is Map ? response.data['content'] : response.data;
    return (data as List).map((x) => BookModel.fromJson(x)).toList();
  }

  Future<List<NotificationModel>> getNotifications() async {
    final response = await apiClient.dio.get(ApiConstants.notifications);
    final data = response.data is Map ? response.data['content'] : response.data;
    return (data as List).map((x) => NotificationModel.fromJson(x)).toList();
  }

  Future<List<FineModel>> getFines() async {
    final response = await apiClient.dio.get(ApiConstants.fines);
    final data = response.data is Map ? response.data['content'] : response.data;
    return (data as List).map((x) => FineModel.fromJson(x)).toList();
  }

  Future<void> payFine(int id, String method) async {
    await apiClient.dio.post('${ApiConstants.fines}/$id/pay', data: {
      'method': method,
      'amountPaid': 999999999,
    });
  }

  Future<List<ReviewModel>> getReviews(int bookId) async {
    final response = await apiClient.dio.get(ApiConstants.reviews, queryParameters: {'bookId': bookId});
    final data = response.data is Map ? response.data['content'] : response.data;
    return (data as List).map((x) => ReviewModel.fromJson(x)).toList();
  }

  Future<void> submitReview(int bookId, int rating, String comment) async {
    await apiClient.dio.post(ApiConstants.reviews, data: {
      'bookId': bookId,
      'rating': rating,
      'comment': comment,
    });
  }
}
