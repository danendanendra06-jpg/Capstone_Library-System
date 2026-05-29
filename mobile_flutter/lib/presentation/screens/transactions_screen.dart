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
      appBar: AppBar(title: Text('Borrowing History')),
      body: txProvider.isLoading
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: txProvider.transactions.length,
              itemBuilder: (context, index) {
                final tx = txProvider.transactions[index];
                return Card(
                  margin: EdgeInsets.all(8.0),
                  child: ListTile(
                    title: Text(tx.bookTitle),
                    subtitle: Text('Borrowed: ${tx.borrowDate}\nReturned: ${tx.returnDate ?? "Not returned"}'),
                    trailing: Chip(
                      label: Text(tx.status),
                      backgroundColor: tx.status == 'RETURNED' ? Colors.green : Colors.orange,
                    ),
                  ),
                );
              },
            ),
    );
  }
}
