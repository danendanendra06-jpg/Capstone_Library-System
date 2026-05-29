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

  Book({
    required this.id,
    required this.title,
    required this.author,
    required this.description,
    required this.coverUrl,
    required this.isAvailable,
  });
}

class Transaction {
  final int id;
  final int bookId;
  final String bookTitle;
  final String borrowDate;
  final String? dueDate;
  final String? returnDate;
  final String status;

  Transaction({
    required this.id,
    required this.bookId,
    required this.bookTitle,
    required this.borrowDate,
    this.dueDate,
    this.returnDate,
    required this.status,
  });
}
