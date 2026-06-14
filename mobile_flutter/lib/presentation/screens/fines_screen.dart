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
                      SizedBox(height: 16),
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
