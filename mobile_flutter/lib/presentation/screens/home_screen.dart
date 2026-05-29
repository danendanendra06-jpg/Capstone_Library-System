import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';
import '../../domain/entities.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => Provider.of<BookProvider>(context, listen: false).loadBooks());
  }

  @override
  Widget build(BuildContext context) {
    final bookProvider = Provider.of<BookProvider>(context);
    final cart = Provider.of<CartProvider>(context, listen: false);

    return Scaffold(
      appBar: AppBar(title: Text('Library Home')),
      body: bookProvider.isLoading
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: bookProvider.books.length,
              itemBuilder: (context, index) {
                final book = bookProvider.books[index];
                return BookCard(book: book, onAdd: () => cart.addToCart(book));
              },
            ),
    );
  }
}

class BookCard extends StatelessWidget {
  final Book book;
  final VoidCallback onAdd;

  BookCard({required this.book, required this.onAdd});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.all(8.0),
      child: ListTile(
        leading: Image.network(book.coverUrl, width: 50, fit: BoxFit.cover, errorBuilder: (_, __, ___) => Icon(Icons.book)),
        title: Text(book.title),
        subtitle: Text(book.author),
        trailing: book.isAvailable
            ? IconButton(icon: Icon(Icons.add_shopping_cart), onPressed: onAdd)
            : Text('Unavailable', style: TextStyle(color: Colors.red)),
      ),
    );
  }
}
