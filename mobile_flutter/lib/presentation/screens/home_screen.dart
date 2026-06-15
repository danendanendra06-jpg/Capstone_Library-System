import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';
import '../../domain/entities.dart';
import '../../domain/repositories.dart';

import 'dummy_screens.dart';
import 'profile_screen.dart';
import 'notifications_screen.dart';
import 'fines_screen.dart';
import 'book_details_screen.dart';
import 'borrows_screen.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final _searchCtrl = TextEditingController();
  bool _isSearching = false;
  int? _selectedCategoryId;
  List<Category> _categories = [];

  @override
  void initState() {
    super.initState();
    Future.microtask(() async {
      final repo = Provider.of<CategoryRepository>(context, listen: false);
      final cats = await repo.getCategories();
      if (mounted) {
        setState(() {
          _categories = cats;
        });
      }
      Provider.of<BookProvider>(context, listen: false).loadBooks();
      Provider.of<BookProvider>(context, listen: false).loadPopularBooks();
    });
  }

  @override
  Widget build(BuildContext context) {
    final bookProvider = Provider.of<BookProvider>(context);
    final cart = Provider.of<CartProvider>(context, listen: false);
    final auth = Provider.of<AuthProvider>(context);

    final displayBooks = _isSearching ? bookProvider.searchResults : bookProvider.books;

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text('Library', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        iconTheme: IconThemeData(color: Colors.black87),
        actions: [
          IconButton(
            icon: Icon(Icons.notifications_none, color: Colors.black87),
            onPressed: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => NotificationsScreen()));
            },
          )
        ],
      ),
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: [
            UserAccountsDrawerHeader(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [Colors.blueAccent, Colors.purpleAccent],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
              ),
              currentAccountPicture: CircleAvatar(
                backgroundColor: Colors.white,
                child: Icon(Icons.person, size: 40, color: Colors.blueAccent),
              ),
              accountName: Text(auth.user?.name ?? 'User', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
              accountEmail: Text(auth.user?.email ?? ''),
            ),
            ListTile(
              leading: Icon(Icons.person_outline),
              title: Text('Profile'),
              onTap: () {
                Navigator.pop(context);
                Navigator.push(context, MaterialPageRoute(builder: (_) => ProfileScreen()));
              },
            ),
            ListTile(
              leading: Icon(Icons.category_outlined),
              title: Text('Categories'),
              onTap: () {
                Navigator.pop(context);
                ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Please use the category dropdown on the home screen.')));
              },
            ),
            ListTile(
              leading: Icon(Icons.history),
              title: Text('Borrowing History'),
              onTap: () {
                Navigator.pop(context);
                Navigator.push(context, MaterialPageRoute(builder: (_) => BorrowsScreen()));
              },
            ),
            ListTile(
              leading: Icon(Icons.notifications_active_outlined),
              title: Text('Notifications'),
              onTap: () {
                Navigator.pop(context);
                Navigator.push(context, MaterialPageRoute(builder: (_) => NotificationsScreen()));
              },
            ),
            ListTile(
              leading: Icon(Icons.money_off_outlined),
              title: Text('Fines & Penalties'),
              onTap: () {
                Navigator.pop(context);
                Navigator.push(context, MaterialPageRoute(builder: (_) => FinesScreen()));
              },
            ),
            ListTile(
              leading: Icon(Icons.settings_outlined),
              title: Text('Settings'),
              onTap: () {
                Navigator.pop(context);
                Navigator.push(context, MaterialPageRoute(builder: (_) => SettingsScreen()));
              },
            ),
            Divider(),
            ListTile(
              leading: Icon(Icons.logout, color: Colors.red),
              title: Text('Logout', style: TextStyle(color: Colors.red)),
              onTap: () {
                Navigator.pop(context);
                auth.logout();
              },
            ),
          ],
        ),
      ),
      body: bookProvider.isLoading && bookProvider.books.isEmpty
          ? Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: () async {
                _searchCtrl.clear();
                setState(() {
                  _isSearching = false;
                  _selectedCategoryId = null;
                });
                final cats = await Provider.of<CategoryRepository>(context, listen: false).getCategories();
                if (mounted) {
                  setState(() => _categories = cats);
                }
                await bookProvider.loadBooks();
                await bookProvider.loadPopularBooks();
              },
              child: CustomScrollView(
                slivers: [
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Discover', style: TextStyle(fontSize: 32, fontWeight: FontWeight.bold, color: Colors.black87)),
                          SizedBox(height: 16),
                          TextField(
                            controller: _searchCtrl,
                            decoration: InputDecoration(
                              hintText: 'Cari judul, penulis, atau ISBN',
                              prefixIcon: Icon(Icons.search, color: Colors.grey[400]),
                              suffixIcon: _isSearching
                                  ? IconButton(
                                      icon: Icon(Icons.clear, color: Colors.grey[400]),
                                      onPressed: () {
                                        _searchCtrl.clear();
                                        setState(() => _isSearching = false);
                                      },
                                    )
                                  : null,
                              filled: true,
                              fillColor: Colors.white,
                              border: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(12),
                                borderSide: BorderSide.none,
                              ),
                              contentPadding: EdgeInsets.symmetric(vertical: 14),
                            ),
                            onChanged: (val) {
                              if (val.isEmpty) {
                                setState(() => _isSearching = false);
                              }
                            },
                            onSubmitted: (val) {
                              if (val.isNotEmpty) {
                                setState(() => _isSearching = true);
                                bookProvider.searchBooks(val);
                              }
                            },
                          ),
                          SizedBox(height: 16),
                          if (_categories.isNotEmpty)
                            Container(
                              padding: EdgeInsets.symmetric(horizontal: 16),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: DropdownButtonHideUnderline(
                                child: DropdownButton<int?>(
                                  isExpanded: true,
                                  value: _selectedCategoryId,
                                  hint: Text('Semua Kategori'),
                                  items: [
                                    DropdownMenuItem<int?>(
                                      value: null,
                                      child: Text('Semua Kategori'),
                                    ),
                                    ..._categories.map((c) => DropdownMenuItem<int?>(
                                      value: c.id,
                                      child: Text(c.name),
                                    ))
                                  ],
                                  onChanged: (val) {
                                    setState(() {
                                      _selectedCategoryId = val;
                                      _isSearching = false;
                                      _searchCtrl.clear();
                                    });
                                    bookProvider.loadBooks(categoryId: val);
                                  },
                                ),
                              ),
                            ),
                          SizedBox(height: 24),
                        ],
                      ),
                    ),
                  ),
                  SliverPadding(
                    padding: EdgeInsets.symmetric(horizontal: 16.0),
                    sliver: SliverToBoxAdapter(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          if (!_isSearching && bookProvider.popularBooks.isNotEmpty) ...[
                            Text('Popular Books', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                            SizedBox(height: 12),
                            Container(
                              height: 280,
                              child: ListView.builder(
                                scrollDirection: Axis.horizontal,
                                itemCount: bookProvider.popularBooks.length,
                                itemBuilder: (context, index) {
                                  final book = bookProvider.popularBooks[index];
                                  return Container(
                                    width: 160,
                                    margin: EdgeInsets.only(right: 16),
                                    child: ModernBookCard(book: book, onAdd: () {
                                      cart.addToCart(book);
                                      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('${book.title} added to cart')));
                                    }, isHorizontal: true),
                                  );
                                },
                              ),
                            ),
                            SizedBox(height: 24),
                          ],
                          Text(_isSearching ? 'Search Results' : 'Latest Books', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                          SizedBox(height: 12),
                        ],
                      ),
                    ),
                  ),
                  SliverPadding(
                    padding: EdgeInsets.symmetric(horizontal: 16.0),
                    sliver: displayBooks.isEmpty
                        ? SliverToBoxAdapter(
                            child: Padding(
                              padding: EdgeInsets.only(top: 20.0),
                              child: Center(
                                child: Text(
                                  _isSearching ? 'No books found' : 'No books available',
                                  style: TextStyle(fontSize: 16, color: Colors.grey[600]),
                                ),
                              ),
                            ),
                          )
                        : SliverList(
                            delegate: SliverChildBuilderDelegate(
                              (context, index) {
                                final book = displayBooks[index];
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
                                  isHorizontal: false,
                                );
                              },
                              childCount: displayBooks.length,
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
  final bool isHorizontal;

  ModernBookCard({required this.book, required this.onAdd, this.isHorizontal = false});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.only(bottom: 16.0),
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: InkWell(
        borderRadius: BorderRadius.circular(16),
        onTap: () {
          Navigator.push(context, MaterialPageRoute(builder: (_) => BookDetailsScreen(book: book)));
        },
        child: Padding(
          padding: const EdgeInsets.all(12.0),
          child: isHorizontal
              ? Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    ClipRRect(
                      borderRadius: BorderRadius.circular(12),
                      child: Container(
                        width: double.infinity,
                        height: 140,
                        color: Colors.grey[200],
                        child: book.coverUrl.isNotEmpty
                            ? Image.network(book.coverUrl, fit: BoxFit.cover, errorBuilder: (_, __, ___) => Icon(Icons.book, size: 40, color: Colors.grey[400]))
                            : Icon(Icons.book, size: 40, color: Colors.grey[400]),
                      ),
                    ),
                    SizedBox(height: 12),
                    Text(
                      book.title,
                      style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.black87),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    Spacer(),
                    book.isAvailable
                        ? FilledButton.tonal(
                            onPressed: onAdd,
                            style: FilledButton.styleFrom(
                              minimumSize: Size(double.infinity, 36),
                              padding: EdgeInsets.symmetric(horizontal: 8),
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                            ),
                            child: Text('Borrow', style: TextStyle(fontSize: 12)),
                          )
                        : Container(
                            alignment: Alignment.center,
                            padding: EdgeInsets.symmetric(vertical: 8),
                            decoration: BoxDecoration(color: Colors.red[50], borderRadius: BorderRadius.circular(8)),
                            child: Text('Checked Out', style: TextStyle(color: Colors.red[700], fontSize: 12, fontWeight: FontWeight.bold)),
                          ),
                  ],
                )
              : Row(
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
      ),
    );
  }
}
