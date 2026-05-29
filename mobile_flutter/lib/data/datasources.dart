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
      'email': email,
      'password': password,
    });
    return response.data['token'];
  }

  Future<String> register(String name, String email, String password) async {
    final response = await apiClient.dio.post(ApiConstants.register, data: {
      'name': name,
      'email': email,
      'password': password,
    });
    return response.data['token'];
  }

  Future<UserModel> getProfile() async {
    final response = await apiClient.dio.get(ApiConstants.profile);
    return UserModel.fromJson(response.data);
  }

  Future<List<BookModel>> getBooks() async {
    final response = await apiClient.dio.get(ApiConstants.books);
    return (response.data as List).map((x) => BookModel.fromJson(x)).toList();
  }

  Future<List<BookModel>> searchBooks(String query) async {
    final response = await apiClient.dio.get(ApiConstants.search, queryParameters: {'q': query});
    return (response.data as List).map((x) => BookModel.fromJson(x)).toList();
  }

  Future<BookModel> getBookDetails(int id) async {
    final response = await apiClient.dio.get('${ApiConstants.books}/$id');
    return BookModel.fromJson(response.data);
  }

  Future<void> borrowBook(int bookId) async {
    await apiClient.dio.post(ApiConstants.borrow, data: {'bookId': bookId});
  }

  Future<List<TransactionModel>> getTransactions() async {
    final response = await apiClient.dio.get(ApiConstants.transactions);
    return (response.data as List).map((x) => TransactionModel.fromJson(x)).toList();
  }
}
