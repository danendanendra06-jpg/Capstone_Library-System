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
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text('Library', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        actions: [
          IconButton(
            icon: Icon(Icons.notifications_none, color: Colors.black87),
            onPressed: () {},
          )
        ],
      ),
      body: bookProvider.isLoading
          ? Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: () => bookProvider.loadBooks(),
              child: CustomScrollView(
                slivers: [
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Discover', style: TextStyle(fontSize: 32, fontWeight: FontWeight.bold, color: Colors.black87)),
                          Text('New and trending books', style: TextStyle(fontSize: 16, color: Colors.grey[600])),
                          SizedBox(height: 24),
                        ],
                      ),
                    ),
                  ),
                  SliverPadding(
                    padding: EdgeInsets.symmetric(horizontal: 16.0),
                    sliver: SliverList(
                      delegate: SliverChildBuilderDelegate(
                        (context, index) {
                          final book = bookProvider.books[index];
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
                            },
                          );
                        },
                        childCount: bookProvider.books.length,
                      ),
                    ),
                  ),
                ],
              ),
            ),
    );
  }
}

class ModernBookCard extends StatelessWidget {
  final Book book;
  final VoidCallback onAdd;

  ModernBookCard({required this.book, required this.onAdd});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.only(bottom: 16.0),
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(12.0),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Container(
                width: 80,
                height: 120,
                color: Colors.grey[200],
                child: book.coverUrl.isNotEmpty
                    ? Image.network(book.coverUrl, fit: BoxFit.cover, errorBuilder: (_, __, ___) => Icon(Icons.book, size: 40, color: Colors.grey[400]))
                    : Icon(Icons.book, size: 40, color: Colors.grey[400]),
              ),
            ),
            SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    book.title,
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.black87),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  SizedBox(height: 4),
                  Text(
                    book.author,
                    style: TextStyle(fontSize: 14, color: Colors.grey[600]),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      book.isAvailable
                          ? Container(
                              padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                              decoration: BoxDecoration(color: Colors.green[50], borderRadius: BorderRadius.circular(8)),
                              child: Text('Available', style: TextStyle(color: Colors.green[700], fontSize: 12, fontWeight: FontWeight.bold)),
                            )
                          : Container(
                              padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                              decoration: BoxDecoration(color: Colors.red[50], borderRadius: BorderRadius.circular(8)),
                              child: Text('Checked Out', style: TextStyle(color: Colors.red[700], fontSize: 12, fontWeight: FontWeight.bold)),
                            ),
                      book.isAvailable
                          ? FilledButton.tonal(
                              onPressed: onAdd,
                              style: FilledButton.styleFrom(
                                padding: EdgeInsets.symmetric(horizontal: 16),
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                              ),
                              child: Text('Borrow'),
                            )
                          : SizedBox.shrink(),
                    ],
                  )
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
