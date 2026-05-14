import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/services/push_notification_helper.dart';
import '../../../core/theme/app_theme.dart';

class PengeluaranForm extends StatefulWidget {
  final Map<String, dynamic>? item;
  const PengeluaranForm({super.key, this.item});

  @override
  State<PengeluaranForm> createState() => _PengeluaranFormState();
}

class _PengeluaranFormState extends State<PengeluaranForm> {
  final _formKey = GlobalKey<FormState>();
  final _keteranganCtrl = TextEditingController();
  final _nominalCtrl = TextEditingController();
  String? _kategori;
  DateTime? _tanggal;
  bool _loading = false;

  final _categories = [
    'Operasional',
    'Gaji',
    'Pembelian Stok',
    'Sewa',
    'Listrik & Air',
    'Lainnya',
  ];

  bool get _isEdit => widget.item != null;

  @override
  void initState() {
    super.initState();
    if (_isEdit) {
      final item = widget.item!;
      _keteranganCtrl.text = item['keterangan'] ?? '';
      _nominalCtrl.text = item['nominal']?.toString() ?? '';
      _kategori = item['kategori'];
      try {
        _tanggal = DateTime.parse(item['tanggal'].toString());
      } catch (_) {}
    } else {
      _tanggal = DateTime.now();
    }
  }

  @override
  void dispose() {
    _keteranganCtrl.dispose();
    _nominalCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _loading = true);

    final body = {
      'keterangan': _keteranganCtrl.text,
      'nominal': double.tryParse(_nominalCtrl.text) ?? 0,
      'kategori': _kategori ?? 'Lainnya',
      'tanggal': DateFormat('yyyy-MM-dd').format(_tanggal ?? DateTime.now()),
    };

    final res = _isEdit
        ? await ApiService.put(
            '$kPengeluaranBase/${widget.item!['id']}', body)
        : await ApiService.post(kPengeluaranBase, body);

    setState(() => _loading = false);
    if (mounted) {
      PushNotificationHelper.show(
        context,
        title: res['success'] == true ? 'Notifikasi Sistem' : 'Peringatan Sistem',
        message: res['success'] == true
            ? (_isEdit ? 'Data pengeluaran berhasil diperbarui.' : 'Data pengeluaran baru berhasil dicatat.')
            : (res['message'] ?? 'Gagal menyimpan pengeluaran.'),
        isSuccess: res['success'] == true,
      );
      if (res['success'] == true) Navigator.pop(context, true);
    }
  }

  @override
  Widget build(BuildContext context) {
    return DraggableScrollableSheet(
      initialChildSize: 0.75,
      maxChildSize: 0.9,
      minChildSize: 0.5,
      builder: (_, ctrl) => Container(
        decoration: BoxDecoration(
          color: AppTheme.bgCard,
          borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
        ),
        child: Column(
          children: [
            const SizedBox(height: 12),
            Container(
              width: 40,
              height: 4,
              decoration: BoxDecoration(
                  color: AppTheme.borderColor,
                  borderRadius: BorderRadius.circular(2)),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
              child: Row(
                children: [
                  Text(
                    _isEdit ? 'Edit Pengeluaran' : 'Tambah Pengeluaran',
                    style: GoogleFonts.inter(
                      color: AppTheme.textPrimary,
                      fontSize: 18,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  const Spacer(),
                  IconButton(
                    icon: Icon(Icons.close, color: AppTheme.textSecondary),
                    onPressed: () => Navigator.pop(context),
                  ),
                ],
              ),
            ),
            Divider(color: AppTheme.borderColor, height: 1),
            Expanded(
              child: ListView(
                controller: ctrl,
                padding: const EdgeInsets.all(20),
                children: [
                  Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _label('Keterangan'),
                        const SizedBox(height: 8),
                        TextFormField(
                          controller: _keteranganCtrl,
                          maxLines: 3,
                          style: GoogleFonts.inter(
                              color: AppTheme.textPrimary, fontSize: 14),
                          decoration: const InputDecoration(
                              hintText: 'Keterangan pengeluaran...'),
                          validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                        ),
                        const SizedBox(height: 16),
                        _label('Nominal (Rp)'),
                        const SizedBox(height: 8),
                        TextFormField(
                          controller: _nominalCtrl,
                          keyboardType: TextInputType.number,
                          style: GoogleFonts.inter(
                              color: AppTheme.textPrimary, fontSize: 14),
                          decoration: const InputDecoration(
                            hintText: '0',
                            prefixIcon:
                                Icon(Icons.attach_money_rounded, size: 18),
                          ),
                          validator: (v) => v!.isEmpty ? 'Wajib diisi' : null,
                        ),
                        const SizedBox(height: 16),
                        _label('Kategori'),
                        const SizedBox(height: 8),
                        DropdownButtonFormField<String>(
                          value: _kategori,
                          dropdownColor: AppTheme.bgSecondary,
                          decoration: InputDecoration(
                            hintText: 'Pilih kategori',
                            hintStyle: GoogleFonts.inter(
                                color: AppTheme.textMuted),
                          ),
                          items: _categories
                              .map((c) => DropdownMenuItem(
                                    value: c,
                                    child: Text(c,
                                        style: GoogleFonts.inter(
                                            color: AppTheme.textPrimary)),
                                  ))
                              .toList(),
                          onChanged: (v) => setState(() => _kategori = v),
                        ),
                        const SizedBox(height: 16),
                        _label('Tanggal'),
                        const SizedBox(height: 8),
                        GestureDetector(
                          onTap: _pickDate,
                          child: Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 16, vertical: 14),
                            decoration: BoxDecoration(
                              color: AppTheme.bgSecondary,
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: AppTheme.borderColor),
                            ),
                            child: Row(
                              children: [
                                Icon(Icons.calendar_today_outlined,
                                    color: AppTheme.textMuted, size: 18),
                                const SizedBox(width: 12),
                                Text(
                                  _tanggal != null
                                      ? DateFormat('d MMMM yyyy', 'id_ID')
                                          .format(_tanggal!)
                                      : 'Pilih tanggal',
                                  style: GoogleFonts.inter(
                                      color: AppTheme.textPrimary),
                                ),
                              ],
                            ),
                          ),
                        ),
                        const SizedBox(height: 24),
                        SizedBox(
                          width: double.infinity,
                          height: 52,
                          child: ElevatedButton(
                            onPressed: _loading ? null : _submit,
                            child: _loading
                                ? const SizedBox(
                                    width: 20,
                                    height: 20,
                                    child: CircularProgressIndicator(
                                        strokeWidth: 2,
                                        color: Colors.white))
                                : Text(
                                    _isEdit ? 'Perbarui' : 'Simpan',
                                    style: GoogleFonts.inter(
                                        fontSize: 15,
                                        fontWeight: FontWeight.w600,
                                        color: Colors.white),
                                  ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _pickDate() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: _tanggal ?? DateTime.now(),
      firstDate: DateTime(2020),
      lastDate: DateTime.now(),
      builder: (ctx, child) => Theme(
        data: Theme.of(ctx).copyWith(
          colorScheme: ColorScheme.dark(
              primary: AppTheme.primaryColor, surface: AppTheme.bgCard),
        ),
        child: child!,
      ),
    );
    if (picked != null) setState(() => _tanggal = picked);
  }

  Widget _label(String t) => Text(
        t,
        style: GoogleFonts.inter(
            color: AppTheme.textSecondary,
            fontSize: 13,
            fontWeight: FontWeight.w500),
      );
}
