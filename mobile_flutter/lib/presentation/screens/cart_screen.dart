import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';

String _getMonthName(int month) {
  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  return months[month - 1];
}

class CartScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context);
    final txProvider = Provider.of<TransactionProvider>(context, listen: false);

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text('Keranjang Peminjaman', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        backgroundColor: Colors.transparent,
        elevation: 0,
      ),
      body: cart.cart.isEmpty
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.remove_shopping_cart_outlined, size: 100, color: Colors.grey[300]),
                  SizedBox(height: 16),
                  Text('Keranjang masih kosong', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.black54)),
                  Text('Jelajahi perpustakaan untuk menemukan buku', style: TextStyle(color: Colors.grey[600])),
                ],
              ),
            )
          : Column(
              children: [
                Expanded(
                  child: ListView.builder(
                    padding: EdgeInsets.all(16),
                    itemCount: cart.cart.length,
                    itemBuilder: (context, index) {
                      final book = cart.cart[index];
                      return Card(
                        elevation: 1,
                        margin: EdgeInsets.only(bottom: 12),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        child: ListTile(
                          contentPadding: EdgeInsets.all(12),
                          leading: ClipRRect(
                            borderRadius: BorderRadius.circular(8),
                            child: Container(
                              width: 50,
                              height: 70,
                              color: Colors.grey[200],
                              child: book.coverUrl.isNotEmpty 
                                ? Image.network(book.coverUrl, fit: BoxFit.cover, errorBuilder: (_, __, ___) => Icon(Icons.book, color: Colors.grey[400]))
                                : Icon(Icons.book, color: Colors.grey[400]),
                            ),
                          ),
                          title: Text(book.title, style: TextStyle(fontWeight: FontWeight.bold)),
                          subtitle: Text(book.author, style: TextStyle(color: Colors.grey[600])),
                          trailing: IconButton(
                            icon: Icon(Icons.remove_circle_outline, color: Colors.red[300]),
                            onPressed: () => cart.removeFromCart(book),
                          ),
                        ),
                      );
                    },
                  ),
                ),
                Container(
                  padding: const EdgeInsets.all(24.0),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    boxShadow: [
                      BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: Offset(0, -5)),
                    ],
                    borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
                  ),
                  child: SafeArea(
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text('Jumlah Buku', style: TextStyle(fontSize: 16, color: Colors.grey[600])),
                            Text('${cart.cart.length}', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                          ],
                        ),
                        SizedBox(height: 24),
                        SizedBox(
                          width: double.infinity,
                          child: FilledButton(
                            style: FilledButton.styleFrom(
                              padding: EdgeInsets.symmetric(vertical: 16),
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                            ),
                            onPressed: () {
                              final now = DateTime.now();
                              final due = now.add(Duration(days: 14));
                              final strNow = '${now.day} ${_getMonthName(now.month)} ${now.year}';
                              final strDue = '${due.day} ${_getMonthName(due.month)} ${due.year}';

                              showDialog(
                                context: context,
                                builder: (ctx) {
                                  DateTime selectedDate = now.add(Duration(days: 14));
                                  return StatefulBuilder(
                                    builder: (context, setState) {
                                      final strNow = '${now.day} ${_getMonthName(now.month)} ${now.year}';
                                      final strSelected = '${selectedDate.day} ${_getMonthName(selectedDate.month)} ${selectedDate.year}';

                                      return AlertDialog(
                                        title: Text('Konfirmasi Peminjaman'),
                                        content: Column(
                                          mainAxisSize: MainAxisSize.min,
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Text('Jumlah Buku : ${cart.cart.length}', style: TextStyle(fontWeight: FontWeight.bold)),
                                            SizedBox(height: 12),
                                            Text('Tanggal Pinjam :\n$strNow'),
                                            SizedBox(height: 12),
                                            Text('Tanggal Pengembalian :', style: TextStyle(fontWeight: FontWeight.bold)),
                                            SizedBox(height: 8),
                                            InkWell(
                                              onTap: () async {
                                                final date = await showDatePicker(
                                                  context: context,
                                                  initialDate: selectedDate,
                                                  firstDate: now,
                                                  lastDate: now.add(Duration(days: 14)),
                                                );
                                                if (date != null) {
                                                  setState(() {
                                                    selectedDate = date;
                                                  });
                                                }
                                              },
                                              child: Container(
                                                padding: EdgeInsets.symmetric(horizontal: 12, vertical: 12),
                                                decoration: BoxDecoration(
                                                  border: Border.all(color: Colors.grey),
                                                  borderRadius: BorderRadius.circular(8),
                                                ),
                                                child: Row(
                                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                                  children: [
                                                    Text(strSelected, style: TextStyle(fontSize: 16)),
                                                    Icon(Icons.calendar_today, size: 20, color: Colors.blueAccent),
                                                  ],
                                                ),
                                              ),
                                            ),
                                            SizedBox(height: 16),
                                            Text(
                                              'Maksimal peminjaman 14 hari dari tanggal pinjam.',
                                              style: TextStyle(fontStyle: FontStyle.italic, fontSize: 13, color: Colors.grey[700]),
                                            ),
                                          ],
                                        ),
                                        actions: [
                                          TextButton(
                                            onPressed: () => Navigator.pop(ctx),
                                            child: Text('Batal', style: TextStyle(color: Colors.grey)),
                                          ),
                                          FilledButton(
                                            onPressed: () async {
                                              Navigator.pop(ctx);
                                              final error = await txProvider.checkout(cart.cart, dueDate: selectedDate.toIso8601String());
                                              if (error == null) {
                                                cart.clearCart();
                                                ScaffoldMessenger.of(context).showSnackBar(
                                                  SnackBar(
                                                    content: Text('Peminjaman berhasil dilakukan.\nJumlah buku: ${cart.cart.length}\nTanggal pengembalian: $strSelected'),
                                                    backgroundColor: Colors.green,
                                                    behavior: SnackBarBehavior.floating,
                                                  ),
                                                );
                                              } else {
                                                ScaffoldMessenger.of(context).showSnackBar(
                                                  SnackBar(
                                                    content: Text('Checkout gagal: $error'),
                                                    backgroundColor: Colors.red,
                                                    behavior: SnackBarBehavior.floating,
                                                  ),
                                                );
                                              }
                                            },
                                            child: Text('Konfirmasi Peminjaman'),
                                          ),
                                        ],
                                      );
                                    }
                                  );
                                },
                              );
                            },
                            child: txProvider.isLoading 
                              ? SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                              : Text('Checkout', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                          ),
                        ),
                      ],
                    ),
                  ),
                )
              ],
            ),
    );
  }
}
