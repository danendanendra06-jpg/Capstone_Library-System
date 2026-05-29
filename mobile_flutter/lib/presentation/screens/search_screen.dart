import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';
import 'home_screen.dart';

class SearchScreen extends StatelessWidget {
  final _searchCtrl = TextEditingController();

  @override
  Widget build(BuildContext context) {
    final bookProvider = Provider.of<BookProvider>(context);
    final cart = Provider.of<CartProvider>(context, listen: false);

    return Scaffold(
      appBar: AppBar(title: Text('Search Books')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: TextField(
              controller: _searchCtrl,
              decoration: InputDecoration(
                labelText: 'Search by title or author',
                suffixIcon: IconButton(
                  icon: Icon(Icons.search),
                  onPressed: () => bookProvider.searchBooks(_searchCtrl.text),
                ),
                border: OutlineInputBorder(),
              ),
              onSubmitted: (val) => bookProvider.searchBooks(val),
            ),
          ),
          Expanded(
            child: bookProvider.isLoading
                ? Center(child: CircularProgressIndicator())
                : ListView.builder(
                    itemCount: bookProvider.searchResults.length,
                    itemBuilder: (context, index) {
                      final book = bookProvider.searchResults[index];
                      return BookCard(book: book, onAdd: () => cart.addToCart(book));
                    },
                  ),
          ),
        ],
      ),
    );
  }
}
