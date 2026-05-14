import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/theme/app_theme.dart';
import '../widgets/pengeluaran_form.dart';

class PengeluaranScreen extends StatefulWidget {
  const PengeluaranScreen({super.key});

  @override
  State<PengeluaranScreen> createState() => _PengeluaranScreenState();
}

class _PengeluaranScreenState extends State<PengeluaranScreen> {
  List<dynamic> _data = [];
  bool _loading = true;
  int _currentPage = 1;
  int _lastPage = 1;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load({int page = 1}) async {
    setState(() => _loading = true);
    final res = await ApiService.get('$kPengeluaranBase?page=$page');
    if (mounted) {
      setState(() {
        _loading = false;
        if (res['success'] == true) {
          final d = res['data'];
          if (d is Map) {
            _data = d['data'] ?? [];
            _currentPage = d['current_page'] ?? 1;
            _lastPage = d['last_page'] ?? 1;
          } else if (d is List) {
            _data = d;
          }
        }
      });
    }
  }

  Future<void> _delete(int id) async {
    final confirm = await showDialog<bool>(
          context: context,
          builder: (_) => AlertDialog(
            backgroundColor: AppTheme.bgCard,
            shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16)),
            title: Text('Hapus Data',
                style: GoogleFonts.inter(
                    color: AppTheme.textPrimary,
                    fontWeight: FontWeight.w600)),
            content: Text('Yakin ingin menghapus pengeluaran ini?',
                style: GoogleFonts.inter(color: AppTheme.textSecondary)),
            actions: [
              TextButton(
                  onPressed: () => Navigator.pop(context, false),
                  child: Text('Batal',
                      style: GoogleFonts.inter(
                          color: AppTheme.textSecondary))),
              ElevatedButton(
                onPressed: () => Navigator.pop(context, true),
                style: ElevatedButton.styleFrom(
                    backgroundColor: AppTheme.accentRed),
                child: Text('Hapus',
                    style: GoogleFonts.inter(color: Colors.white)),
              ),
            ],
          ),
        ) ??
        false;
    if (!confirm) return;
    final res = await ApiService.delete('$kPengeluaranBase/$id');
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(res['success'] == true
            ? 'Data berhasil dihapus'
            : (res['message'] ?? 'Gagal')),
        backgroundColor:
            res['success'] == true ? AppTheme.accentGreen : AppTheme.accentRed,
      ));
      if (res['success'] == true) _load(page: _currentPage);
    }
  }

  void _openForm({Map<String, dynamic>? item}) async {
    final result = await showModalBottomSheet<bool>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => PengeluaranForm(item: item),
    );
    if (result == true) _load(page: _currentPage);
  }

  double _parseDouble(dynamic value) {
    if (value == null) return 0.0;
    if (value is num) return value.toDouble();
    if (value is String) return double.tryParse(value) ?? 0.0;
    return 0.0;
  }

  String _formatRupiah(dynamic val) {
    final amount = _parseDouble(val);
    return 'Rp ${NumberFormat('#,###', 'id_ID').format(amount)}';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgPrimary,
      body: RefreshIndicator(
        color: AppTheme.primaryColor,
        backgroundColor: AppTheme.bgCard,
        onRefresh: () => _load(page: _currentPage),
        child: CustomScrollView(
          slivers: [
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Manajemen Pengeluaran',
                      style: GoogleFonts.inter(
                        color: AppTheme.textPrimary,
                        fontSize: 20,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    Text(
                      '${_data.length} data pengeluaran',
                      style: GoogleFonts.inter(
                          color: AppTheme.textSecondary, fontSize: 12),
                    ),
                    const SizedBox(height: 16),
                    if (_loading)
                      const Center(
                          child: Padding(
                        padding: EdgeInsets.only(top: 80),
                        child: CircularProgressIndicator(
                            color: AppTheme.primaryColor),
                      ))
                    else
                      ..._data
                          .map((item) => _buildCard(item))
                          .toList(),
                    if (_lastPage > 1) ...[
                      const SizedBox(height: 16),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          IconButton(
                            onPressed: _currentPage > 1
                                ? () => _load(page: _currentPage - 1)
                                : null,
                            icon: Icon(Icons.chevron_left_rounded,
                                color: AppTheme.textSecondary),
                          ),
                          Text('$_currentPage / $_lastPage',
                              style: GoogleFonts.inter(
                                  color: AppTheme.textSecondary)),
                          IconButton(
                            onPressed: _currentPage < _lastPage
                                ? () => _load(page: _currentPage + 1)
                                : null,
                            icon: Icon(Icons.chevron_right_rounded,
                                color: AppTheme.textSecondary),
                          ),
                        ],
                      ),
                    ],
                    const SizedBox(height: 80),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _openForm(),
        backgroundColor: AppTheme.primaryColor,
        icon: const Icon(Icons.add, color: Colors.white),
        label: Text('Tambah',
            style: GoogleFonts.inter(
                color: Colors.white, fontWeight: FontWeight.w600)),
      ),
    );
  }

  Widget _buildCard(Map<String, dynamic> item) {
    String dateStr = '';
    try {
      dateStr = DateFormat('d MMM yyyy', 'id_ID')
          .format(DateTime.parse(item['tanggal'].toString()));
    } catch (_) {}

    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.bgCard,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppTheme.borderColor),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: AppTheme.accentRed.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Text(dateStr,
                    style: GoogleFonts.inter(
                        color: AppTheme.accentRed, fontSize: 11)),
              ),
              const Spacer(),
              Text(
                _formatRupiah(item['nominal']),
                style: GoogleFonts.inter(
                  color: AppTheme.accentRed,
                  fontSize: 16,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Text(
            item['keterangan'] ?? '-',
            style: GoogleFonts.inter(
              color: AppTheme.textPrimary,
              fontSize: 14,
              fontWeight: FontWeight.w500,
            ),
          ),
          if (item['kategori'] != null) ...[
            const SizedBox(height: 4),
            Container(
              padding:
                  const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: AppTheme.accentOrange.withOpacity(0.1),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(item['kategori'],
                  style: GoogleFonts.inter(
                      color: AppTheme.accentOrange, fontSize: 11)),
            ),
          ],
          const SizedBox(height: 12),
          Divider(color: AppTheme.borderColor, height: 1),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.end,
            children: [
              GestureDetector(
                onTap: () => _openForm(item: item),
                child: Container(
                  padding: const EdgeInsets.symmetric(
                      horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: AppTheme.primaryColor.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(
                        color: AppTheme.primaryColor.withOpacity(0.3)),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Icon(Icons.edit_outlined,
                          size: 14, color: AppTheme.primaryColor),
                      const SizedBox(width: 4),
                      Text('Edit',
                          style: GoogleFonts.inter(
                              color: AppTheme.primaryColor,
                              fontSize: 12,
                              fontWeight: FontWeight.w500)),
                    ],
                  ),
                ),
              ),
              const SizedBox(width: 8),
              GestureDetector(
                onTap: () => _delete(item['id']),
                child: Container(
                  padding: const EdgeInsets.symmetric(
                      horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: AppTheme.accentRed.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(
                        color: AppTheme.accentRed.withOpacity(0.3)),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Icon(Icons.delete_outline,
                          size: 14, color: AppTheme.accentRed),
                      const SizedBox(width: 4),
                      Text('Hapus',
                          style: GoogleFonts.inter(
                              color: AppTheme.accentRed,
                              fontSize: 12,
                              fontWeight: FontWeight.w500)),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
