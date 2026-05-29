import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers.dart';

class TransactionsScreen extends StatefulWidget {
  @override
  _TransactionsScreenState createState() => _TransactionsScreenState();
}

class _TransactionsScreenState extends State<TransactionsScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => Provider.of<TransactionProvider>(context, listen: false).loadTransactions());
  }

  @override
  Widget build(BuildContext context) {
    final txProvider = Provider.of<TransactionProvider>(context);

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
          : txProvider.transactions.isEmpty
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
                  itemCount: txProvider.transactions.length,
                  itemBuilder: (context, index) {
                    final tx = txProvider.transactions[index];
                    final isReturned = tx.status.toUpperCase() == 'RETURNED';
                    final isOverdue = tx.status.toUpperCase() == 'OVERDUE';
                    
                    Color statusColor = Colors.orange;
                    if (isReturned) statusColor = Colors.green;
                    if (isOverdue) statusColor = Colors.red;

                    return Card(
                      elevation: 1,
                      margin: EdgeInsets.only(bottom: 16),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
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
                              children: [
                                Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text('Borrowed On', style: TextStyle(fontSize: 12, color: Colors.grey[600])),
                                    Text(tx.borrowDate, style: TextStyle(fontWeight: FontWeight.w500)),
                                  ],
                                ),
                                Column(
                                  crossAxisAlignment: CrossAxisAlignment.end,
                                  children: [
                                    Text(isReturned ? 'Returned On' : 'Due Date', style: TextStyle(fontSize: 12, color: Colors.grey[600])),
                                    Text(
                                      tx.returnDate ?? (tx.dueDate ?? 'Pending'), 
                                      style: TextStyle(fontWeight: FontWeight.w500, color: isOverdue ? Colors.red : Colors.black87)
                                    ),
                                  ],
                                )
                              ],
                            )
                          ],
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}
