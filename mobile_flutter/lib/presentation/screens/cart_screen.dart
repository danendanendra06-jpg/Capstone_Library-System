import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';

class CartScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context);
    final txProvider = Provider.of<TransactionProvider>(context, listen: false);

    return Scaffold(
      appBar: AppBar(title: Text('Borrowing Cart')),
      body: cart.cart.isEmpty
          ? Center(child: Text('Cart is empty'))
          : Column(
              children: [
                Expanded(
                  child: ListView.builder(
                    itemCount: cart.cart.length,
                    itemBuilder: (context, index) {
                      final book = cart.cart[index];
                      return ListTile(
                        title: Text(book.title),
                        subtitle: Text(book.author),
                        trailing: IconButton(
                          icon: Icon(Icons.delete, color: Colors.red),
                          onPressed: () => cart.removeFromCart(book),
                        ),
                      );
                    },
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () async {
                        if (await txProvider.checkout(cart.cart)) {
                          cart.clearCart();
                          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Books borrowed successfully')));
                        } else {
                          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Checkout failed')));
                        }
                      },
                      child: Text('Checkout'),
                    ),
                  ),
                )
              ],
            ),
    );
  }
}
