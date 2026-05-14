import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/theme/app_theme.dart';
import '../widgets/penghasilan_form.dart';

class PenghasilanScreen extends StatefulWidget {
  const PenghasilanScreen({super.key});

  @override
  State<PenghasilanScreen> createState() => _PenghasilanScreenState();
}

class _PenghasilanScreenState extends State<PenghasilanScreen> {
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
    final res = await ApiService.get('$kPenghasilanBase?page=$page');
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
    final confirm = await _showDeleteDialog();
    if (!confirm) return;
    final res = await ApiService.delete('$kPenghasilanBase/$id');
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(res['success'] == true
            ? 'Data berhasil dihapus'
            : (res['message'] ?? 'Gagal menghapus')),
        backgroundColor:
            res['success'] == true ? AppTheme.accentGreen : AppTheme.accentRed,
      ));
      if (res['success'] == true) _load();
    }
  }

  Future<bool> _showDeleteDialog() async {
    return await showDialog<bool>(
          context: context,
          builder: (_) => AlertDialog(
            backgroundColor: AppTheme.bgCard,
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            title: Text('Hapus Data',
                style: GoogleFonts.inter(
                    color: AppTheme.textPrimary,
                    fontWeight: FontWeight.w600)),
            content: Text('Yakin ingin menghapus data ini?',
                style: GoogleFonts.inter(color: AppTheme.textSecondary)),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context, false),
                child: Text('Batal',
                    style:
                        GoogleFonts.inter(color: AppTheme.textSecondary)),
              ),
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
  }

  void _openForm({Map<String, dynamic>? item}) async {
    final result = await showModalBottomSheet<bool>(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => PenghasilanForm(item: item),
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
                    _buildHeader(),
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
                      _buildPagination(),
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

  Widget _buildHeader() => Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Manajemen Penghasilan',
                  style: GoogleFonts.inter(
                    color: AppTheme.textPrimary,
                    fontSize: 20,
                    fontWeight: FontWeight.w700,
                  ),
                ),
                Text(
                  '${_data.length} transaksi ditemukan',
                  style: GoogleFonts.inter(
                      color: AppTheme.textSecondary, fontSize: 12),
                ),
              ],
            ),
          ),
        ],
      );

  Widget _buildCard(Map<String, dynamic> item) {
    String dateStr = '';
    try {
      final d = DateTime.parse(item['tanggal'].toString());
      dateStr = DateFormat('d MMM yyyy', 'id_ID').format(d);
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
                  color: AppTheme.accentGreen.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Text(
                  dateStr,
                  style: GoogleFonts.inter(
                      color: AppTheme.accentGreen, fontSize: 11),
                ),
              ),
              const Spacer(),
              Text(
                _formatRupiah(item['total']),
                style: GoogleFonts.inter(
                  color: AppTheme.accentGreen,
                  fontSize: 16,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Text(
            item['nama_pemilik'] ?? '-',
            style: GoogleFonts.inter(
              color: AppTheme.textPrimary,
              fontSize: 14,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            'Montir: ${item['nama_montir'] ?? '-'}',
            style: GoogleFonts.inter(
                color: AppTheme.textSecondary, fontSize: 12),
          ),
          if (item['service'] != null && item['service'].isNotEmpty) ...[
            const SizedBox(height: 4),
            Text(
              item['service'],
              style:
                  GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 12),
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
            ),
          ],
          const SizedBox(height: 12),
          Divider(color: AppTheme.borderColor, height: 1),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.end,
            children: [
              _actionBtn(Icons.edit_outlined, 'Edit', AppTheme.primaryColor,
                  () => _openForm(item: item)),
              const SizedBox(width: 8),
              _actionBtn(Icons.delete_outline, 'Hapus', AppTheme.accentRed,
                  () => _delete(item['id'])),
            ],
          ),
        ],
      ),
    );
  }

  Widget _actionBtn(
          IconData icon, String label, Color color, VoidCallback onTap) =>
      GestureDetector(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
          decoration: BoxDecoration(
            color: color.withOpacity(0.1),
            borderRadius: BorderRadius.circular(8),
            border: Border.all(color: color.withOpacity(0.3)),
          ),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Icon(icon, size: 14, color: color),
              const SizedBox(width: 4),
              Text(label,
                  style: GoogleFonts.inter(
                      color: color,
                      fontSize: 12,
                      fontWeight: FontWeight.w500)),
            ],
          ),
        ),
      );

  Widget _buildPagination() => Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          IconButton(
            onPressed:
                _currentPage > 1 ? () => _load(page: _currentPage - 1) : null,
            icon: const Icon(Icons.chevron_left_rounded),
            color: AppTheme.textSecondary,
          ),
          Text(
            '$_currentPage / $_lastPage',
            style: GoogleFonts.inter(color: AppTheme.textSecondary),
          ),
          IconButton(
            onPressed: _currentPage < _lastPage
                ? () => _load(page: _currentPage + 1)
                : null,
            icon: const Icon(Icons.chevron_right_rounded),
            color: AppTheme.textSecondary,
          ),
        ],
      );
}
