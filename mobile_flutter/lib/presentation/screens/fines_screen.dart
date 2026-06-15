import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../domain/repositories.dart';
import '../../domain/entities.dart';

class FinesScreen extends StatefulWidget {
  @override
  _FinesScreenState createState() => _FinesScreenState();
}

class _FinesScreenState extends State<FinesScreen> {
  late Future<List<Fine>> _finesFuture;

  @override
  void initState() {
    super.initState();
    _loadFines();
  }

  void _loadFines() {
    setState(() {
      _finesFuture = Provider.of<FineRepository>(context, listen: false).getFines();
    });
  }

  void _showPaymentDialog(BuildContext context, Fine fine) {
    showModalBottomSheet(
      context: context,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (context) {
        return Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Pilih Metode Pembayaran', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
              SizedBox(height: 16),
              ListTile(
                leading: Icon(Icons.account_balance_wallet, color: Colors.blue),
                title: Text('DANA'),
                onTap: () => _processPayment(context, fine.id, 'DANA'),
              ),
              ListTile(
                leading: Icon(Icons.account_balance_wallet, color: Colors.green),
                title: Text('GoPay'),
                onTap: () => _processPayment(context, fine.id, 'GOPAY'),
              ),
              ListTile(
                leading: Icon(Icons.credit_card, color: Colors.orange),
                title: Text('Kartu ATM / Debit'),
                onTap: () => _processPayment(context, fine.id, 'ATM'),
              ),
            ],
          ),
        );
      }
    );
  }

  void _processPayment(BuildContext context, int fineId, String method) async {
    Navigator.pop(context); // close modal
    showDialog(context: context, barrierDismissible: false, builder: (_) => Center(child: CircularProgressIndicator()));
    try {
      await Provider.of<FineRepository>(context, listen: false).payFine(fineId, method);
      Navigator.pop(context); // close loading
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Pembayaran berhasil melalui $method!'), backgroundColor: Colors.green));
        _loadFines();
      }
    } catch (e) {
      Navigator.pop(context); // close loading
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal memproses pembayaran'), backgroundColor: Colors.red));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        title: Text('Fines & Penalties', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.black87)),
        backgroundColor: Colors.white,
        elevation: 1,
        iconTheme: IconThemeData(color: Colors.black87),
      ),
      body: FutureBuilder<List<Fine>>(
        future: _finesFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) return Center(child: CircularProgressIndicator());
          if (!snapshot.hasData || snapshot.data!.isEmpty) return Center(child: Text('No fines recorded. Great job!', style: TextStyle(color: Colors.grey[600], fontSize: 16)));
          
          return ListView.builder(
            padding: EdgeInsets.all(16),
            itemCount: snapshot.data!.length,
            itemBuilder: (context, index) {
              final fine = snapshot.data![index];
              return Card(
                elevation: 1,
                margin: EdgeInsets.only(bottom: 12),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text('Fine #${fine.id}', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.grey[600])),
                          Container(
                            padding: EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: fine.isPaid ? Colors.green[50] : Colors.red[50],
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Text(
                              fine.isPaid ? 'Paid' : 'Unpaid',
                              style: TextStyle(color: fine.isPaid ? Colors.green[700] : Colors.red[700], fontSize: 12, fontWeight: FontWeight.bold),
                            ),
                          )
                        ],
                      ),
                      SizedBox(height: 12),
                      Text('Amount: Rp ${fine.amount.toStringAsFixed(0)}', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.black87)),
                      SizedBox(height: 4),
                      Text('Reason: ${fine.reason}', style: TextStyle(fontSize: 14, color: Colors.grey[700])),
                      if (fine.paymentMethod != null) ...[
                        SizedBox(height: 4),
                        Text('Method: ${fine.paymentMethod}', style: TextStyle(fontSize: 14, color: Colors.blue[700], fontWeight: FontWeight.bold)),
                      ],
                      if (!fine.isPaid) ...[
                        SizedBox(height: 16),
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton(
                            onPressed: () => _showPaymentDialog(context, fine),
                            child: Text('Pay Now'),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Colors.blue[600],
                              foregroundColor: Colors.white,
                              padding: EdgeInsets.symmetric(vertical: 12),
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                            ),
                          ),
                        )
                      ] else ...[
                        SizedBox(height: 16),
                      ],
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
