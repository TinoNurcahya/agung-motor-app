import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:shimmer/shimmer.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/services/push_notification_helper.dart';
import '../../../core/theme/app_theme.dart';

class ProdukScreen extends StatefulWidget {
  const ProdukScreen({super.key});
  @override
  State<ProdukScreen> createState() => _ProdukScreenState();
}

class _ProdukScreenState extends State<ProdukScreen> {
  List<dynamic> _list = [];
  bool _isLoading = true;
  String _search = '';
  String _kat = 'Semua';
  List<String> _katList = ['Semua'];
  int _total = 0, _menipis = 0;
  final _searchCtrl = TextEditingController();
  final _fmt = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  @override
  void initState() {
    super.initState();
    _fetch();
  }

  Future<void> _fetch() async {
    setState(() => _isLoading = true);
    try {
      String url = kProdukBase;
      final p = <String>[];
      if (_search.isNotEmpty) p.add('search=${Uri.encodeComponent(_search)}');
      if (_kat != 'Semua') p.add('kategori=${Uri.encodeComponent(_kat)}');
      if (p.isNotEmpty) url += '?${p.join('&')}';
      final res = await ApiService.get(url);
      if (res != null && mounted) {
        dynamic raw = res['data'] ?? res['produk'] ?? (res is List ? res : []);
        if (raw is Map) {
          raw = raw['data'] ?? [];
        }
        final cats = <String>{'Semua'};
        for (final item in raw) {
          if (item['kategori'] != null) cats.add(item['kategori'].toString());
        }
        setState(() {
          _list = raw;
          _total = raw.length;
          _menipis = raw.where((x) { final s = x['stok'] ?? 0; return s < 10 && s > 0; }).length;
          _katList = cats.toList();
        });
      }
    } catch (_) {} finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _delete(int id) async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        backgroundColor: AppTheme.bgCard,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        title: Text('Hapus Produk', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontWeight: FontWeight.w600)),
        content: Text('Yakin ingin menghapus?', style: GoogleFonts.inter(color: AppTheme.textSecondary)),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: Text('Batal', style: GoogleFonts.inter(color: AppTheme.textSecondary))),
          ElevatedButton(style: ElevatedButton.styleFrom(backgroundColor: AppTheme.accentRed), onPressed: () => Navigator.pop(context, true), child: const Text('Hapus', style: TextStyle(color: Colors.white))),
        ],
      ),
    );
    if (ok != true) return;
    await ApiService.delete('$kProdukBase/$id');
    _fetch();
    if (mounted) {
      PushNotificationHelper.show(
        context,
        title: 'Notifikasi Sistem',
        message: 'Data produk berhasil dihapus dari inventaris.',
        isSuccess: true,
      );
    }
  }

  void _showForm({Map<String, dynamic>? produk}) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: AppTheme.bgCard,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(24))),
      builder: (_) => _ProdukForm(produk: produk, onSaved: _fetch),
    );
  }

  Color _stokColor(int s) => s <= 0 ? AppTheme.accentRed : s < 10 ? AppTheme.accentOrange : AppTheme.accentGreen;
  String _stokLabel(int s) => s <= 0 ? 'Habis' : s < 10 ? 'Menipis' : 'Aman';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgPrimary,
      body: RefreshIndicator(
        color: AppTheme.primaryColor,
        onRefresh: _fetch,
        child: CustomScrollView(slivers: [
          SliverToBoxAdapter(child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Text('Inventaris Produk', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 20, fontWeight: FontWeight.w700)),
              const SizedBox(height: 4),
              Text('Kelola stok suku cadang bengkel', style: GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 13)),
              const SizedBox(height: 16),
              Row(children: [
                _Chip(label: 'Total', value: '$_total', color: AppTheme.primaryColor),
                const SizedBox(width: 12),
                _Chip(label: 'Menipis', value: '$_menipis', color: AppTheme.accentOrange),
              ]),
              const SizedBox(height: 16),
              TextField(
                controller: _searchCtrl,
                style: GoogleFonts.inter(color: AppTheme.textPrimary),
                decoration: InputDecoration(
                  hintText: 'Cari produk...',
                  prefixIcon: Icon(Icons.search, color: AppTheme.textMuted),
                  suffixIcon: _search.isNotEmpty ? IconButton(icon: Icon(Icons.clear, color: AppTheme.textMuted), onPressed: () { _searchCtrl.clear(); setState(() => _search = ''); _fetch(); }) : null,
                ),
                onChanged: (v) { setState(() => _search = v); Future.delayed(const Duration(milliseconds: 500), _fetch); },
              ),
              const SizedBox(height: 12),
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                child: Row(children: _katList.map((k) {
                  final sel = _kat == k;
                  return Padding(padding: const EdgeInsets.only(right: 8), child: FilterChip(
                    label: Text(k, style: GoogleFonts.inter(fontSize: 12, color: sel ? Colors.white : AppTheme.textSecondary)),
                    selected: sel,
                    onSelected: (_) { setState(() => _kat = k); _fetch(); },
                    backgroundColor: AppTheme.bgSecondary,
                    selectedColor: AppTheme.primaryColor,
                    checkmarkColor: Colors.white,
                    side: BorderSide(color: sel ? AppTheme.primaryColor : AppTheme.borderColor),
                  ));
                }).toList()),
              ),
            ]),
          )),
          if (_isLoading)
            SliverToBoxAdapter(child: _shimmer())
          else if (_list.isEmpty)
            SliverToBoxAdapter(child: Center(child: Padding(padding: const EdgeInsets.all(48), child: Column(children: [
              Icon(Icons.inventory_2_outlined, size: 64, color: AppTheme.textMuted),
              const SizedBox(height: 16),
              Text('Tidak ada produk', style: GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 16)),
            ]))))
          else
            SliverPadding(
              padding: const EdgeInsets.fromLTRB(16, 0, 16, 100),
              sliver: SliverList(delegate: SliverChildBuilderDelegate((_, i) => _card(_list[i]), childCount: _list.length)),
            ),
        ]),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showForm(),
        backgroundColor: AppTheme.primaryColor,
        icon: const Icon(Icons.add, color: Colors.white),
        label: Text('Tambah', style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w600)),
      ),
    );
  }

  Widget _card(Map<String, dynamic> p) {
    final stok = p['stok'] ?? 0;
    final harga = double.tryParse(p['harga'].toString()) ?? 0;
    final sc = _stokColor(stok);
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: AppTheme.bgCard, borderRadius: BorderRadius.circular(16), border: Border.all(color: AppTheme.borderColor)),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [
          Container(width: 48, height: 48, decoration: BoxDecoration(color: AppTheme.primaryColor.withOpacity(0.1), borderRadius: BorderRadius.circular(12)), child: const Icon(Icons.inventory_2_rounded, color: AppTheme.primaryColor, size: 24)),
          const SizedBox(width: 12),
          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Text(p['nama'] ?? '-', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.w600), maxLines: 1, overflow: TextOverflow.ellipsis),
            Text('${p['kategori'] ?? '-'}${p['sku'] != null ? ' · SKU: ${p['sku']}' : ''}', style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 12)),
          ])),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
            decoration: BoxDecoration(color: sc.withOpacity(0.15), borderRadius: BorderRadius.circular(20), border: Border.all(color: sc.withOpacity(0.3))),
            child: Text(_stokLabel(stok), style: GoogleFonts.inter(color: sc, fontSize: 11, fontWeight: FontWeight.w600)),
          ),
        ]),
        const SizedBox(height: 12),
        Divider(color: AppTheme.borderColor, height: 1),
        const SizedBox(height: 12),
        Row(children: [
          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
            Text('Harga', style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 11)),
            Text(_fmt.format(harga), style: GoogleFonts.inter(color: AppTheme.accentGreen, fontSize: 14, fontWeight: FontWeight.w700)),
          ])),
          Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.center, children: [
            Text('Stok', style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 11)),
            Text('$stok unit', style: GoogleFonts.inter(color: sc, fontSize: 14, fontWeight: FontWeight.w700)),
          ])),
          Row(children: [
            IconButton(icon: const Icon(Icons.edit_outlined, color: AppTheme.primaryColor, size: 20), onPressed: () => _showForm(produk: p)),
            IconButton(icon: const Icon(Icons.delete_outline, color: AppTheme.accentRed, size: 20), onPressed: () => _delete(p['id'])),
          ]),
        ]),
      ]),
    );
  }

  Widget _shimmer() => Shimmer.fromColors(
    baseColor: AppTheme.bgSecondary,
    highlightColor: AppTheme.bgCardHover,
    child: Padding(padding: const EdgeInsets.all(16), child: Column(
      children: List.generate(5, (_) => Container(margin: const EdgeInsets.only(bottom: 12), height: 130, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)))),
    )),
  );
}

class _Chip extends StatelessWidget {
  final String label, value;
  final Color color;
  const _Chip({required this.label, required this.value, required this.color});
  @override
  Widget build(BuildContext context) => Expanded(child: Container(
    padding: const EdgeInsets.all(14),
    decoration: BoxDecoration(color: color.withOpacity(0.1), borderRadius: BorderRadius.circular(12), border: Border.all(color: color.withOpacity(0.25))),
    child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
      Text(value, style: GoogleFonts.inter(color: color, fontSize: 22, fontWeight: FontWeight.w700)),
      Text(label, style: GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 12)),
    ]),
  ));
}

class _ProdukForm extends StatefulWidget {
  final Map<String, dynamic>? produk;
  final VoidCallback onSaved;
  const _ProdukForm({this.produk, required this.onSaved});
  @override
  State<_ProdukForm> createState() => _ProdukFormState();
}

class _ProdukFormState extends State<_ProdukForm> {
  final _formKey = GlobalKey<FormState>();
  final _namaCtrl = TextEditingController();
  final _skuCtrl = TextEditingController();
  final _hargaCtrl = TextEditingController();
  final _stokCtrl = TextEditingController();
  final _deskCtrl = TextEditingController();
  String? _kat;
  bool _saving = false;
  final _kats = ['Oli', 'Ban', 'Kampas Rem', 'Aki', 'Busi', 'Filter', 'Kelistrikan', 'Body', 'Lainnya'];

  @override
  void initState() {
    super.initState();
    final p = widget.produk;
    if (p != null) {
      _namaCtrl.text = p['nama'] ?? '';
      _kat = _kats.contains(p['kategori']) ? p['kategori'] : null;
      _skuCtrl.text = p['sku'] ?? '';
      _hargaCtrl.text = p['harga']?.toString() ?? '';
      _stokCtrl.text = p['stok']?.toString() ?? '';
      _deskCtrl.text = p['deskripsi'] ?? '';
    }
  }

  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _saving = true);
    try {
      final data = {
        'nama': _namaCtrl.text,
        'kategori': _kat,
        'sku': _skuCtrl.text.isNotEmpty ? _skuCtrl.text : null,
        'harga': double.tryParse(_hargaCtrl.text.replaceAll(RegExp(r'[^0-9]'), '')) ?? 0,
        'stok': int.tryParse(_stokCtrl.text) ?? 0,
        'deskripsi': _deskCtrl.text.isNotEmpty ? _deskCtrl.text : null,
      };
      if (widget.produk != null) {
        await ApiService.put('$kProdukBase/${widget.produk!['id']}', data);
      } else {
        await ApiService.post(kProdukBase, data);
      }
      if (mounted) {
        Navigator.pop(context);
        widget.onSaved();
        PushNotificationHelper.show(
          context,
          title: 'Notifikasi Sistem',
          message: widget.produk != null
              ? 'Data produk berhasil diperbarui.'
              : 'Produk baru berhasil ditambahkan ke inventaris.',
          isSuccess: true,
        );
      }
    } catch (e) {
      if (mounted) {
        PushNotificationHelper.show(
          context,
          title: 'Peringatan Sistem',
          message: 'Gagal menyimpan produk: $e',
          isSuccess: false,
        );
      }
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Form(key: _formKey, child: Column(crossAxisAlignment: CrossAxisAlignment.start, mainAxisSize: MainAxisSize.min, children: [
          Center(child: Container(width: 40, height: 4, decoration: BoxDecoration(color: AppTheme.borderColor, borderRadius: BorderRadius.circular(2)))),
          const SizedBox(height: 20),
          Text(widget.produk != null ? 'Edit Produk' : 'Tambah Produk', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 18, fontWeight: FontWeight.w700)),
          const SizedBox(height: 20),
          _field('Nama Produk *', _namaCtrl, Icons.inventory_2_outlined, v: (x) => x!.isEmpty ? 'Wajib diisi' : null),
          const SizedBox(height: 12),
          DropdownButtonFormField<String>(
            value: _kat,
            dropdownColor: AppTheme.bgCard,
            style: GoogleFonts.inter(color: AppTheme.textPrimary),
            decoration: InputDecoration(labelText: 'Kategori *', prefixIcon: Icon(Icons.category_outlined, color: AppTheme.textMuted)),
            items: _kats.map((k) => DropdownMenuItem(value: k, child: Text(k))).toList(),
            onChanged: (v) => setState(() => _kat = v),
            validator: (v) => v == null ? 'Wajib dipilih' : null,
          ),
          const SizedBox(height: 12),
          _field('SKU / Kode', _skuCtrl, Icons.qr_code_outlined),
          const SizedBox(height: 12),
          _field('Harga (Rp) *', _hargaCtrl, Icons.attach_money_rounded, type: TextInputType.number, v: (x) => x!.isEmpty ? 'Wajib diisi' : null),
          const SizedBox(height: 12),
          _field('Stok *', _stokCtrl, Icons.numbers_rounded, type: TextInputType.number, v: (x) => x!.isEmpty ? 'Wajib diisi' : null),
          const SizedBox(height: 12),
          TextFormField(
            controller: _deskCtrl, maxLines: 2,
            style: GoogleFonts.inter(color: AppTheme.textPrimary),
            decoration: InputDecoration(labelText: 'Deskripsi', prefixIcon: Icon(Icons.description_outlined, color: AppTheme.textMuted)),
          ),
          const SizedBox(height: 24),
          SizedBox(width: double.infinity, child: ElevatedButton(
            onPressed: _saving ? null : _save,
            child: _saving ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white)) : Text(widget.produk != null ? 'Simpan Perubahan' : 'Tambah Produk'),
          )),
          const SizedBox(height: 8),
        ])),
      ),
    );
  }

  Widget _field(String label, TextEditingController c, IconData icon, {TextInputType? type, String? Function(String?)? v}) => TextFormField(
    controller: c, keyboardType: type,
    style: GoogleFonts.inter(color: AppTheme.textPrimary),
    decoration: InputDecoration(labelText: label, prefixIcon: Icon(icon, color: AppTheme.textMuted)),
    validator: v,
  );
}
