import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../domain/repositories.dart';
import '../../domain/entities.dart';
import '../providers.dart';
import 'home_screen.dart';

class SettingsScreen extends StatefulWidget {
  @override
  _SettingsScreenState createState() => _SettingsScreenState();
}

class _SettingsScreenState extends State<SettingsScreen> {
  bool _notificationsEnabled = true;

  @override
  Widget build(BuildContext context) {
    final themeProvider = Provider.of<ThemeProvider>(context);
    final isDark = themeProvider.isDarkMode;

    return Scaffold(
      appBar: AppBar(title: Text('Settings', style: TextStyle(color: isDark ? Colors.white : Colors.black87)), backgroundColor: isDark ? Colors.grey[900] : Colors.white, iconTheme: IconThemeData(color: isDark ? Colors.white : Colors.black87), elevation: 1),
      body: ListView(
        children: [
          SwitchListTile(
            title: Text('Push Notifications'),
            subtitle: Text('Receive reminders and updates'),
            value: _notificationsEnabled,
            onChanged: (val) => setState(() => _notificationsEnabled = val),
          ),
          SwitchListTile(
            title: Text('Dark Mode'),
            subtitle: Text('Switch to a darker theme'),
            value: isDark,
            onChanged: (val) => themeProvider.toggleTheme(val),
          ),
          ListTile(
            title: Text('About'),
            trailing: Icon(Icons.chevron_right),
            onTap: () {
              showAboutDialog(context: context, applicationName: 'Library App', applicationVersion: '1.0.0');
            },
          )
        ],
      ),
    );
  }
}

class CategoriesScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final repo = Provider.of<CategoryRepository>(context, listen: false);
    return Scaffold(
      appBar: AppBar(title: Text('Categories', style: TextStyle(color: Colors.black87)), backgroundColor: Colors.white, iconTheme: IconThemeData(color: Colors.black87), elevation: 1),
      body: FutureBuilder<List<Category>>(
        future: repo.getCategories(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) return Center(child: CircularProgressIndicator());
          if (!snapshot.hasData || snapshot.data!.isEmpty) return Center(child: Text('No categories found.'));
          return ListView.builder(
            itemCount: snapshot.data!.length,
            itemBuilder: (context, index) {
              final cat = snapshot.data![index];
              return ListTile(
                leading: Icon(Icons.category, color: Colors.blueAccent),
                title: Text(cat.name, style: TextStyle(fontWeight: FontWeight.bold)),
                subtitle: Text(cat.description),
                onTap: () {
                  // Navigate to books filtered by category
                  Navigator.push(context, MaterialPageRoute(builder: (_) => CategoryBooksScreen(category: cat)));
                },
              );
            },
          );
        },
      ),
    );
  }
}

class CategoryBooksScreen extends StatefulWidget {
  final Category category;
  CategoryBooksScreen({required this.category});
  @override
  _CategoryBooksScreenState createState() => _CategoryBooksScreenState();
}

class _CategoryBooksScreenState extends State<CategoryBooksScreen> {
  List<Book> books = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    Provider.of<BookRepository>(context, listen: false).getBooksByCategory(widget.category.id).then((value) {
      setState(() {
        books = value;
        isLoading = false;
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context, listen: false);
    return Scaffold(
      appBar: AppBar(title: Text(widget.category.name, style: TextStyle(color: Colors.black87)), backgroundColor: Colors.white, iconTheme: IconThemeData(color: Colors.black87), elevation: 1),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : books.isEmpty
              ? Center(child: Text('No books in this category.'))
              : ListView.builder(
                  padding: EdgeInsets.all(16),
                  itemCount: books.length,
                  itemBuilder: (context, index) {
                    final book = books[index];
                    return ModernBookCard(
                      book: book,
                      onAdd: () {
                        cart.addToCart(book);
                        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('${book.title} added to cart')));
                      },
                    );
                  },
                ),
    );
  }
}
