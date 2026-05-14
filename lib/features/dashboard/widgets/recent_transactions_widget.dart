import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../../core/theme/app_theme.dart';

class RecentTransactionsWidget extends StatelessWidget {
  final List<dynamic> transactions;

  const RecentTransactionsWidget({super.key, required this.transactions});

  @override
  Widget build(BuildContext context) {
    if (transactions.isEmpty) {
      return Container(
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          color: AppTheme.bgCard,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: AppTheme.borderColor),
        ),
        child: Center(
          child: Column(
            children: [
              Icon(Icons.receipt_long_outlined,
                  color: AppTheme.textMuted, size: 40),
              const SizedBox(height: 8),
              Text(
                'Belum ada transaksi',
                style: GoogleFonts.inter(color: AppTheme.textMuted),
              ),
            ],
          ),
        ),
      );
    }
    return Column(
      children: transactions
          .map<Widget>((tx) => _TransactionTile(tx: tx))
          .toList(),
    );
  }
}

class _TransactionTile extends StatelessWidget {
  final dynamic tx;
  const _TransactionTile({required this.tx});

  @override
  Widget build(BuildContext context) {
    double parseD(dynamic v) {
      if (v == null) return 0.0;
      if (v is num) return v.toDouble();
      if (v is String) return double.tryParse(v) ?? 0.0;
      return 0.0;
    }

    final isIncome = tx['type'] == 'income';
    final color = isIncome ? AppTheme.accentGreen : AppTheme.accentRed;
    final nominal = parseD(tx['nominal']);
    final formattedNominal = 'Rp ${NumberFormat('#,###', 'id_ID').format(nominal)}';

    String dateStr = '';
    try {
      final d = DateTime.parse(tx['tanggal'].toString());
      dateStr = DateFormat('d MMM yyyy', 'id_ID').format(d);
    } catch (_) {}

    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      decoration: BoxDecoration(
        color: AppTheme.bgCard,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: AppTheme.borderColor),
      ),
      child: Row(
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              color: color.withOpacity(0.12),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Icon(
              isIncome ? Icons.arrow_downward_rounded : Icons.arrow_upward_rounded,
              color: color,
              size: 18,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  (tx['keterangan'] ?? tx['kategori'] ?? '-').toString(),
                  style: GoogleFonts.inter(
                    color: AppTheme.textPrimary,
                    fontSize: 13,
                    fontWeight: FontWeight.w500,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                Text(
                  dateStr,
                  style: GoogleFonts.inter(
                      color: AppTheme.textMuted, fontSize: 11),
                ),
              ],
            ),
          ),
          Text(
            '${isIncome ? '+' : '-'}$formattedNominal',
            style: GoogleFonts.inter(
              color: color,
              fontSize: 13,
              fontWeight: FontWeight.w700,
            ),
          ),
        ],
      ),
    );
  }
}
