import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../domain/repositories.dart';
import '../../domain/entities.dart';
import '../providers.dart';

class BookDetailsScreen extends StatefulWidget {
  final Book book;
  BookDetailsScreen({required this.book});

  @override
  _BookDetailsScreenState createState() => _BookDetailsScreenState();
}

class _BookDetailsScreenState extends State<BookDetailsScreen> {
  List<Review> _reviews = [];
  bool _isLoadingReviews = true;

  @override
  void initState() {
    super.initState();
    _fetchReviews();
  }

  Future<void> _fetchReviews() async {
    try {
      final repo = Provider.of<ReviewRepository>(context, listen: false);
      final reviews = await repo.getReviews(widget.book.id);
      if (mounted) {
        setState(() {
          _reviews = reviews;
          _isLoadingReviews = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _isLoadingReviews = false;
        });
      }
    }
  }
  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context, listen: false);
    final isAvailable = widget.book.stock > 0;

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text('Book Details', style: TextStyle(color: Colors.black87)),
        backgroundColor: Colors.white,
        iconTheme: IconThemeData(color: Colors.black87),
        elevation: 1,
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Book Info
            Container(
              padding: EdgeInsets.all(24),
              color: Colors.grey[50],
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  ClipRRect(
                    borderRadius: BorderRadius.circular(12),
                    child: Container(
                      width: 120,
                      height: 180,
                      color: Colors.grey[200],
                      child: widget.book.coverUrl.isNotEmpty
                          ? Image.network(widget.book.coverUrl, fit: BoxFit.cover, errorBuilder: (_, __, ___) => Icon(Icons.book, size: 60, color: Colors.grey[400]))
                          : Icon(Icons.book, size: 60, color: Colors.grey[400]),
                    ),
                  ),
                  SizedBox(width: 24),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(widget.book.title, style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.black87)),
                        SizedBox(height: 8),
                        Text(widget.book.author, style: TextStyle(fontSize: 16, color: Colors.grey[700])),
                        SizedBox(height: 12),
                        Row(
                          children: [
                            Icon(Icons.category_outlined, size: 16, color: Colors.grey[600]),
                            SizedBox(width: 4),
                            Text(widget.book.categoryName, style: TextStyle(fontSize: 14, color: Colors.grey[600])),
                          ],
                        ),
                        SizedBox(height: 16),
                        Row(
                          children: [
                            Container(
                              padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                              decoration: BoxDecoration(color: isAvailable ? Colors.green[50] : Colors.red[50], borderRadius: BorderRadius.circular(8)),
                              child: Text(isAvailable ? 'Tersedia' : 'Tidak Tersedia', style: TextStyle(color: isAvailable ? Colors.green[700] : Colors.red[700], fontWeight: FontWeight.bold)),
                            ),
                            SizedBox(width: 12),
                            Text('Stok: ${widget.book.stock}', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: Colors.grey[800])),
                          ],
                        )
                      ],
                    ),
                  )
                ],
              ),
            ),
            Padding(
              padding: EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Description', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  SizedBox(height: 12),
                  Text(widget.book.description, style: TextStyle(fontSize: 16, color: Colors.grey[800], height: 1.5)),
                  SizedBox(height: 32),
                  Text('Informasi Tambahan', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  SizedBox(height: 16),
                  Container(
                    padding: EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.blue[50],
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: Colors.blue[100]!),
                    ),
                    child: Column(
                      children: [
                        _buildDetailRow('ISBN', widget.book.isbn ?? '-'),
                        Divider(height: 24),
                        _buildDetailRow('Penerbit', widget.book.publisher ?? '-'),
                        Divider(height: 24),
                        _buildDetailRow('Tahun Terbit', widget.book.publicationYear?.toString() ?? '-'),
                        Divider(height: 24),
                        _buildDetailRow('Lokasi Rak', widget.book.shelfLocation ?? '-'),
                        Divider(height: 24),
                        _buildDetailRow('Harga Buku', widget.book.price != null ? 'Rp ${widget.book.price!.toStringAsFixed(0)}' : '-'),
                      ],
                    ),
                  ),
                  SizedBox(height: 32),
                  Text('Ulasan', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  SizedBox(height: 16),
                  if (_isLoadingReviews)
                    Center(child: CircularProgressIndicator())
                  else if (_reviews.isEmpty)
                    Text('Belum ada ulasan untuk buku ini.', style: TextStyle(color: Colors.grey[600]))
                  else
                    ..._reviews.map((r) => Card(
                      margin: EdgeInsets.only(bottom: 8),
                      child: ListTile(
                        leading: CircleAvatar(child: Text(r.userName.isNotEmpty ? r.userName[0] : 'U')),
                        title: Row(
                          children: [
                            Expanded(child: Text(r.userName, style: TextStyle(fontWeight: FontWeight.bold))),
                            Row(
                              children: List.generate(5, (index) => Icon(
                                index < r.rating ? Icons.star : Icons.star_border,
                                color: Colors.amber,
                                size: 16,
                              )),
                            )
                          ],
                        ),
                        subtitle: Text(r.comment),
                      ),
                    )),
                  SizedBox(height: 16),
                  FilledButton.tonal(
                    onPressed: () {
                      _showAddReviewDialog();
                    },
                    child: Text('Tulis Ulasan'),
                  ),
                  SizedBox(height: 32),
                ],
              ),
            ),
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 10, offset: Offset(0, -2))],
        ),
        child: FilledButton.icon(
          onPressed: isAvailable ? () {
            cart.addToCart(widget.book);
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text('${widget.book.title} ditambahkan ke keranjang'),
                behavior: SnackBarBehavior.floating,
                backgroundColor: Colors.green,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
              ),
            );
          } : null,
          style: FilledButton.styleFrom(
            padding: EdgeInsets.symmetric(vertical: 16),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
          icon: Icon(Icons.add_shopping_cart),
          label: Text('Tambah ke Keranjang', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(
          width: 120,
          child: Text(label, style: TextStyle(color: Colors.grey[600], fontWeight: FontWeight.w500)),
        ),
        Expanded(
          child: Text(value, style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        ),
      ],
    );
  }

  void _showAddReviewDialog() {
    int rating = 5;
    final commentCtrl = TextEditingController();

    showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setStateSB) {
            return AlertDialog(
              title: Text('Tulis Ulasan'),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: List.generate(5, (index) => IconButton(
                      icon: Icon(index < rating ? Icons.star : Icons.star_border, color: Colors.amber),
                      onPressed: () => setStateSB(() => rating = index + 1),
                    )),
                  ),
                  TextField(
                    controller: commentCtrl,
                    decoration: InputDecoration(hintText: 'Komentar Anda'),
                    maxLines: 3,
                  )
                ],
              ),
              actions: [
                TextButton(onPressed: () => Navigator.pop(context), child: Text('Batal')),
                FilledButton(
                  onPressed: () async {
                    if (commentCtrl.text.isEmpty) return;
                    Navigator.pop(context);
                    try {
                      final repo = Provider.of<ReviewRepository>(context, listen: false);
                      await repo.submitReview(widget.book.id, rating, commentCtrl.text);
                      _fetchReviews();
                      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Ulasan berhasil ditambahkan')));
                    } catch (e) {
                      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal menambahkan ulasan')));
                    }
                  },
                  child: Text('Kirim'),
                )
              ],
            );
          }
        );
      }
    );
  }
}
