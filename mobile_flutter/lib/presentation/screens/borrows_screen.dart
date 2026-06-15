import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';

class BorrowsScreen extends StatefulWidget {
  @override
  _BorrowsScreenState createState() => _BorrowsScreenState();
}

class _BorrowsScreenState extends State<BorrowsScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => Provider.of<BorrowProvider>(context, listen: false).loadBorrows());
  }

  @override
  Widget build(BuildContext context) {
    final txProvider = Provider.of<BorrowProvider>(context);

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text('Borrowing History', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        backgroundColor: Colors.transparent,
        elevation: 0,
        iconTheme: IconThemeData(color: Colors.black87),
      ),
      body: txProvider.isLoading
          ? Center(child: CircularProgressIndicator())
          : txProvider.borrows.isEmpty
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.history_toggle_off, size: 80, color: Colors.grey[300]),
                      SizedBox(height: 16),
                      Text('No history found', style: TextStyle(fontSize: 20, color: Colors.grey[600])),
                    ],
                  ),
                )
              : ListView.builder(
                  padding: EdgeInsets.all(16),
                  itemCount: txProvider.borrows.length,
                  itemBuilder: (context, index) {
                    final tx = txProvider.borrows[index];
                    final isReturned = tx.status.toUpperCase() == 'RETURNED';
                    final isOverdue = tx.status.toUpperCase() == 'OVERDUE';
                    
                    Color statusColor = Colors.orange;
                    if (isReturned) statusColor = Colors.green;
                    if (isOverdue) statusColor = Colors.red;

                    return Card(
                      elevation: 1,
                      margin: EdgeInsets.only(bottom: 16),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      child: InkWell(
                        borderRadius: BorderRadius.circular(12),
                        onTap: () {
                          showModalBottomSheet(
                            context: context,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
                            builder: (ctx) => Container(
                              padding: EdgeInsets.all(24),
                              child: Column(
                                mainAxisSize: MainAxisSize.min,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Center(
                                    child: Container(
                                      width: 40, height: 4,
                                      margin: EdgeInsets.only(bottom: 24),
                                      decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(2)),
                                    ),
                                  ),
                                  Text('Detail Borrows', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                                  SizedBox(height: 24),
                                  _DetailRow('Judul Buku', tx.bookTitle),
                                  SizedBox(height: 12),
                                  _DetailRow('Tanggal Pinjam', _formatDate(tx.borrowDate)),
                                  SizedBox(height: 12),
                                  _DetailRow('Batas Pengembalian', tx.dueDate != null ? _formatDate(tx.dueDate!) : '-'),
                                  if (tx.returnDate != null) ...[
                                    SizedBox(height: 12),
                                    _DetailRow('Tanggal Dikembalikan', _formatDate(tx.returnDate!)),
                                    if (tx.returnCondition != null) ...[
                                      SizedBox(height: 12),
                                      _DetailRow('Kondisi Buku', tx.returnCondition!),
                                    ],
                                    if (tx.lateDays != null && tx.lateDays! > 0) ...[
                                      SizedBox(height: 12),
                                      _DetailRow('Terlambat', '${tx.lateDays} hari'),
                                    ],
                                    if (tx.fineAmount != null && tx.fineAmount! > 0) ...[
                                      SizedBox(height: 12),
                                      _DetailRow('Total Denda', 'Rp ${tx.fineAmount!.toStringAsFixed(0)}'),
                                    ],
                                  ],
                                  SizedBox(height: 12),
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text('Status', style: TextStyle(color: Colors.grey[600])),
                                      Container(
                                        padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                                        decoration: BoxDecoration(color: statusColor.withOpacity(0.1), borderRadius: BorderRadius.circular(8)),
                                        child: Text(tx.status.toUpperCase(), style: TextStyle(fontWeight: FontWeight.bold, color: statusColor, fontSize: 12)),
                                      ),
                                    ],
                                  ),
                                  if (isOverdue) ...[
                                    SizedBox(height: 24),
                                    Container(
                                      padding: EdgeInsets.all(16),
                                      decoration: BoxDecoration(color: Colors.red[50], borderRadius: BorderRadius.circular(12), border: Border.all(color: Colors.red[100]!)),
                                      child: Row(
                                        children: [
                                          Icon(Icons.warning_amber_rounded, color: Colors.red),
                                          SizedBox(width: 12),
                                          Expanded(child: Text('Buku ini terlambat dikembalikan. Silakan periksa notifikasi denda atau hubungi admin perpustakaan.', style: TextStyle(color: Colors.red[800], fontSize: 13))),
                                        ],
                                      ),
                                    )
                                  ],
                                  SizedBox(height: 24),
                                  SizedBox(
                                    width: double.infinity,
                                    child: FilledButton(
                                      onPressed: () => Navigator.pop(ctx),
                                      child: Text('Tutup'),
                                    ),
                                  )
                                ],
                              ),
                            ),
                          );
                        },
                        child: Padding(
                          padding: const EdgeInsets.all(16.0),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Container(
                                    padding: EdgeInsets.all(12),
                                    decoration: BoxDecoration(
                                      color: statusColor.withOpacity(0.1),
                                      borderRadius: BorderRadius.circular(12)
                                    ),
                                    child: Icon(
                                      isReturned ? Icons.check_circle : (isOverdue ? Icons.warning : Icons.book),
                                      color: statusColor,
                                    ),
                                  ),
                                  SizedBox(width: 16),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          tx.bookTitle,
                                          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                                        ),
                                        SizedBox(height: 4),
                                        Container(
                                          padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                          decoration: BoxDecoration(
                                            color: statusColor.withOpacity(0.1),
                                            borderRadius: BorderRadius.circular(8)
                                          ),
                                          child: Text(
                                            tx.status.toUpperCase(),
                                            style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: statusColor),
                                          ),
                                        ),
                                      ],
                                    ),
                                  )
                                ],
                              ),
                              Divider(height: 24),
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text('Dipinjam', style: TextStyle(fontSize: 12, color: Colors.grey[600])),
                                        Text(_formatDate(tx.borrowDate), style: TextStyle(fontWeight: FontWeight.w500, fontSize: 13)),
                                      ],
                                    ),
                                  ),
                                  SizedBox(width: 8),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.end,
                                      children: [
                                        Text(isReturned ? 'Dikembalikan' : 'Jatuh Tempo', style: TextStyle(fontSize: 12, color: Colors.grey[600])),
                                        Text(
                                          tx.returnDate != null ? _formatDate(tx.returnDate!) : (tx.dueDate != null ? _formatDate(tx.dueDate!) : 'Pending'), 
                                          style: TextStyle(fontWeight: FontWeight.w500, fontSize: 13, color: isOverdue ? Colors.red : Colors.black87),
                                          textAlign: TextAlign.right,
                                        ),
                                      ],
                                    ),
                                  )
                                ],
                              )
                            ],
                          ),
                        ),
                      ),
                    );
                  },
                ),
    );
  }

  String _formatDate(String isoDate) {
    try {
      final date = DateTime.parse(isoDate);
      return '${date.day.toString().padLeft(2, '0')} ${_monthName(date.month)} ${date.year}';
    } catch (e) {
      return isoDate;
    }
  }

  String _monthName(int month) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return months[month - 1];
  }

  Widget _DetailRow(String label, String value) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        SizedBox(width: 120, child: Text(label, style: TextStyle(color: Colors.grey[600]))),
        Expanded(child: Text(value, style: TextStyle(fontWeight: FontWeight.w600, color: Colors.black87))),
      ],
    );
  }
}
