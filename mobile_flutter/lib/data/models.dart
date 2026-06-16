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
    required super.stock,
    required super.categoryName,
    super.isbn,
    super.publisher,
    super.publicationYear,
  });

  factory BookModel.fromJson(Map<String, dynamic> json) {
    int stock = json['availableCopies'] ?? json['stock'] ?? 0;
    return BookModel(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      author: json['author'] ?? '',
      description: json['description'] ?? '',
      coverUrl: json['coverUrl'] ?? json['cover_image'] ?? 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400&auto=format&fit=crop',
      isAvailable: stock > 0,
      stock: stock,
      categoryName: json['category'] != null ? json['category']['name'] : (json['categoryName'] ?? 'Uncategorized'),
      isbn: json['isbn'],
      publisher: json['publisher'],
      publicationYear: json['publicationYear'] ?? json['publication_year'],
    );
  }
}

class BorrowModel extends Borrow {
  BorrowModel({
    required super.id,
    required super.bookId,
    required super.bookTitle,
    required super.borrowDate,
    super.dueDate,
    super.returnDate,
    required super.status,
    super.returnCondition,
    super.lateDays,
    super.fineAmount,
  });

  factory BorrowModel.fromJson(Map<String, dynamic> json) {
    return BorrowModel(
      id: json['id'] ?? 0,
      bookId: json['book'] != null ? json['book']['id'] : (json['bookId'] ?? 0),
      bookTitle: json['book'] != null ? json['book']['title'] : (json['bookTitle'] ?? ''),
      borrowDate: json['borrowDate'] ?? '',
      dueDate: json['expectedReturnDate'] ?? json['dueDate'],
      returnDate: json['returnDate'],
      status: json['status'] ?? 'UNKNOWN',
      returnCondition: json['returnCondition'] ?? json['return_condition'],
      lateDays: json['lateDays'] ?? json['late_days'],
      fineAmount: json['fineAmount'] != null ? (json['fineAmount'] as num).toDouble() : (json['fine_amount'] != null ? (json['fine_amount'] as num).toDouble() : null),
    );
  }
}

class CategoryModel extends Category {
  CategoryModel({required super.id, required super.name, required super.description});

  factory CategoryModel.fromJson(Map<String, dynamic> json) {
    return CategoryModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'] ?? '',
    );
  }
}

class NotificationModel extends Notification {
  NotificationModel({required super.id, required super.title, required super.message, required super.isRead, required super.sentAt});

  factory NotificationModel.fromJson(Map<String, dynamic> json) {
    return NotificationModel(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      message: json['message'] ?? '',
      isRead: json['isRead'] ?? false,
      sentAt: json['sentAt'] ?? '',
    );
  }
}

class ReviewModel extends Review {
  ReviewModel({required super.id, required super.bookId, required super.rating, required super.comment, required super.reviewDate, required super.userName});

  factory ReviewModel.fromJson(Map<String, dynamic> json) {
    return ReviewModel(
      id: json['id'] ?? 0,
      bookId: json['bookId'] ?? (json['book'] != null ? json['book']['id'] : 0),
      rating: json['rating'] ?? 0,
      comment: json['comment'] ?? json['review_text'] ?? '',
      reviewDate: json['reviewDate'] ?? '',
      userName: json['user'] != null ? (json['user']['username'] ?? json['user']['name'] ?? 'User') : 'User',
    );
  }
}

class FineModel extends Fine {
  FineModel({
    required super.id,
    required super.borrowId,
    required super.amount,
    required super.reason,
    required super.isPaid,
    super.fineType,
    super.paymentMethod,
  });

  factory FineModel.fromJson(Map<String, dynamic> json) {
    String paymentStat = json['paymentStatus'] ?? json['payment_status'] ?? (json['status'] == 'paid' ? 'PAID' : 'UNPAID');
    return FineModel(
      id: json['id'] ?? 0,
      borrowId: json['borrow'] != null ? json['borrow']['id'] : (json['borrowId'] ?? 0),
      amount: (json['amount'] ?? json['fine_amount'] ?? 0).toDouble(),
      reason: json['reason'] ?? '',
      isPaid: paymentStat == 'PAID' || (json['isPaid'] == true),
      fineType: json['fineType'] ?? json['fine_type'],
      paymentMethod: json['paymentMethod'] ?? json['payment_method'],
    );
  }
}
