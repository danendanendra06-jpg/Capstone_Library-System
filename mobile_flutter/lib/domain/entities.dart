class User {
  final int id;
  final String name;
  final String email;

  User({required this.id, required this.name, required this.email});
}

class Book {
  final int id;
  final String title;
  final String author;
  final String description;
  final String coverUrl;
  final bool isAvailable;
  final int stock;
  final String categoryName;
  final String? isbn;
  final String? publisher;
  final int? publicationYear;
  final String? shelfLocation;
  final double? price;

  Book({
    required this.id,
    required this.title,
    required this.author,
    required this.description,
    required this.coverUrl,
    required this.isAvailable,
    required this.stock,
    required this.categoryName,
    this.isbn,
    this.publisher,
    this.publicationYear,
    this.shelfLocation,
    this.price,
  });
}

class Borrow {
  final int id;
  final int bookId;
  final String bookTitle;
  final String borrowDate;
  final String? dueDate;
  final String? returnDate;
  final String status;
  final String? returnCondition;
  final int? lateDays;
  final double? fineAmount;

  Borrow({
    required this.id,
    required this.bookId,
    required this.bookTitle,
    required this.borrowDate,
    this.dueDate,
    this.returnDate,
    required this.status,
    this.returnCondition,
    this.lateDays,
    this.fineAmount,
  });
}

class Category {
  final int id;
  final String name;
  final String description;

  Category({required this.id, required this.name, required this.description});
}

class Notification {
  final int id;
  final String title;
  final String message;
  final bool isRead;
  final String sentAt;

  Notification({required this.id, required this.title, required this.message, required this.isRead, required this.sentAt});
}

class Review {
  final int id;
  final int bookId;
  final int rating;
  final String comment;
  final String reviewDate;
  final String userName;

  Review({required this.id, required this.bookId, required this.rating, required this.comment, required this.reviewDate, required this.userName});
}

class Fine {
  final int id;
  final double amount;
  final String reason;
  final bool isPaid;
  final String? fineType;
  final String? paymentStatus;

  Fine({
    required this.id, 
    required this.amount, 
    required this.reason, 
    required this.isPaid,
    this.fineType,
    this.paymentStatus,
  });
}
