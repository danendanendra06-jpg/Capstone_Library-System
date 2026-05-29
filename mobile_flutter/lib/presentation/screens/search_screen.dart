import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';
import 'home_screen.dart'; // Imports ModernBookCard

class SearchScreen extends StatelessWidget {
  final _searchCtrl = TextEditingController();

  @override
  Widget build(BuildContext context) {
    final bookProvider = Provider.of<BookProvider>(context);
    final cart = Provider.of<CartProvider>(context, listen: false);

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text('Search', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        backgroundColor: Colors.transparent,
        elevation: 0,
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
            decoration: BoxDecoration(
              color: Colors.white,
              boxShadow: [
                BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 4, offset: Offset(0, 4)),
              ]
            ),
            child: TextField(
              controller: _searchCtrl,
              decoration: InputDecoration(
                hintText: 'Search by title, author, or ISBN',
                prefixIcon: Icon(Icons.search, color: Colors.grey[400]),
                suffixIcon: IconButton(
                  icon: Icon(Icons.arrow_forward, color: Theme.of(context).primaryColor),
                  onPressed: () {
                    if (_searchCtrl.text.isNotEmpty) {
                      bookProvider.searchBooks(_searchCtrl.text);
                    }
                  },
                ),
                filled: true,
                fillColor: Colors.grey[100],
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                  borderSide: BorderSide.none,
                ),
                contentPadding: EdgeInsets.symmetric(vertical: 14),
              ),
              onSubmitted: (val) {
                if (val.isNotEmpty) bookProvider.searchBooks(val);
              },
            ),
          ),
          Expanded(
            child: bookProvider.isLoading
                ? Center(child: CircularProgressIndicator())
                : bookProvider.searchResults.isEmpty && _searchCtrl.text.isNotEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.search_off, size: 80, color: Colors.grey[300]),
                            SizedBox(height: 16),
                            Text('No books found', style: TextStyle(fontSize: 20, color: Colors.grey[600])),
                          ],
                        ),
                      )
                    : ListView.builder(
                        padding: EdgeInsets.all(16),
                        itemCount: bookProvider.searchResults.length,
                        itemBuilder: (context, index) {
                          final book = bookProvider.searchResults[index];
                          return ModernBookCard(
                            book: book, 
                            onAdd: () {
                              cart.addToCart(book);
                              ScaffoldMessenger.of(context).showSnackBar(
                                SnackBar(
                                  content: Text('${book.title} added to cart'),
                                  behavior: SnackBarBehavior.floating,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                                ),
                              );
                            }
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }
}
