import '../domain/entities.dart';

class UserModel extends User {
  UserModel({required super.id, required super.name, required super.email});

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
    );
  }
}

class BookModel extends Book {
  BookModel({
    required super.id,
    required super.title,
    required super.author,
    required super.description,
    required super.coverUrl,
    required super.isAvailable,
  });

  factory BookModel.fromJson(Map<String, dynamic> json) {
    return BookModel(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      author: json['author'] ?? '',
      description: json['description'] ?? '',
      coverUrl: json['coverUrl'] ?? 'https://via.placeholder.com/150',
      isAvailable: json['isAvailable'] ?? true,
    );
  }
}

class TransactionModel extends Transaction {
  TransactionModel({
    required super.id,
    required super.bookId,
    required super.bookTitle,
    required super.borrowDate,
    super.dueDate,
    super.returnDate,
    required super.status,
  });

  factory TransactionModel.fromJson(Map<String, dynamic> json) {
    return TransactionModel(
      id: json['id'] ?? 0,
      bookId: json['bookId'] ?? 0,
      bookTitle: json['bookTitle'] ?? '',
      borrowDate: json['borrowDate'] ?? '',
      dueDate: json['dueDate'],
      returnDate: json['returnDate'],
      status: json['status'] ?? 'UNKNOWN',
    );
  }
}
