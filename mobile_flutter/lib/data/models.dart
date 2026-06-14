import '../domain/entities.dart';

class UserModel extends User {
  UserModel({required super.id, required super.name, required super.email});

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? json['username'] ?? '',
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
      coverUrl: json['coverUrl'] ?? json['cover_image'] ?? 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400&auto=format&fit=crop',
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
      bookId: json['book'] != null ? json['book']['id'] : (json['bookId'] ?? 0),
      bookTitle: json['book'] != null ? json['book']['title'] : (json['bookTitle'] ?? ''),
      borrowDate: json['borrowDate'] ?? '',
      dueDate: json['expectedReturnDate'] ?? json['dueDate'],
      returnDate: json['returnDate'],
      status: json['status'] ?? 'UNKNOWN',
    );
  }
}
