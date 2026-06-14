import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';
import 'home_screen.dart';
import 'search_screen.dart';
import 'cart_screen.dart';
import 'profile_screen.dart';

class MainScreen extends StatefulWidget {
  @override
  _MainScreenState createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  int _currentIndex = 0;
  final _screens = [
    HomeScreen(),
    SearchScreen(),
    CartScreen(),
    ProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _screens[_currentIndex],
      floatingActionButton: FloatingActionButton(
        backgroundColor: Theme.of(context).primaryColor,
        child: Stack(
          children: [
            Icon(Icons.collections_bookmark, color: Colors.white),
            Consumer<CartProvider>(
              builder: (_, cart, __) => cart.cart.isNotEmpty
                  ? Positioned(
                      right: 0,
                      top: 0,
                      child: CircleAvatar(
                        radius: 6,
                        backgroundColor: Colors.red,
                      ),
                    )
                  : SizedBox.shrink(),
            ),
          ],
        ),
        onPressed: () {
          Navigator.push(context, MaterialPageRoute(builder: (_) => CartScreen()));
        },
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex == 3 ? 2 : (_currentIndex > 2 ? _currentIndex - 1 : _currentIndex),
        onTap: (i) {
          if (i == 2) {
            setState(() => _currentIndex = 3);
          } else {
            setState(() => _currentIndex = i);
          }
        },
        type: BottomNavigationBarType.fixed,
        items: [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Home'),
          BottomNavigationBarItem(icon: Icon(Icons.search), label: 'Search'),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: 'Profile'),
        ],
      ),
    );
  }
}
