import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/services/push_notification_helper.dart';
import '../../../core/theme/app_theme.dart';

class PenghasilanForm extends StatefulWidget {
  final Map<String, dynamic>? item;

  const PenghasilanForm({super.key, this.item});

  @override
  State<PenghasilanForm> createState() => _PenghasilanFormState();
}

class _PenghasilanFormState extends State<PenghasilanForm> {
  final _formKey = GlobalKey<FormState>();
  final _namaPemilikCtrl = TextEditingController();
  final _namaMontirCtrl = TextEditingController();
  final _nomorPolisiCtrl = TextEditingController();
  final _serviceCtrl = TextEditingController();
  final _sparepartCtrl = TextEditingController();
  final _jasaCtrl = TextEditingController();
  final _sparepartNominalCtrl = TextEditingController();
  final _totalCtrl = TextEditingController();
  DateTime? _tanggal;
  bool _loading = false;

  bool get _isEdit => widget.item != null;

  @override
  void initState() {
    super.initState();
    if (_isEdit) {
      final item = widget.item!;
      _namaPemilikCtrl.text = item['nama_pemilik'] ?? '';
      _namaMontirCtrl.text = item['nama_montir'] ?? '';
      _nomorPolisiCtrl.text = item['nomor_polisi'] ?? '';
      _serviceCtrl.text = item['service'] ?? '';
      _sparepartCtrl.text = item['sparepart'] ?? '';
      _jasaCtrl.text = item['jasa']?.toString() ?? '';
      _sparepartNominalCtrl.text = item['sparepart_nominal']?.toString() ?? '';
      _totalCtrl.text = item['total']?.toString() ?? '';
      try {
        _tanggal = DateTime.parse(item['tanggal'].toString());
      } catch (_) {}
    } else {
      _tanggal = DateTime.now();
    }
  }

  @override
  void dispose() {
    _namaPemilikCtrl.dispose();
    _namaMontirCtrl.dispose();
    _nomorPolisiCtrl.dispose();
    _serviceCtrl.dispose();
    _sparepartCtrl.dispose();
    _jasaCtrl.dispose();
    _sparepartNominalCtrl.dispose();
    _totalCtrl.dispose();
    super.dispose();
  }

  void _autoCalcTotal() {
    final jasa = double.tryParse(_jasaCtrl.text) ?? 0;
    final sparepart = double.tryParse(_sparepartNominalCtrl.text) ?? 0;
    _totalCtrl.text = (jasa + sparepart).toStringAsFixed(0);
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _loading = true);

    final body = {
      'nama_pemilik': _namaPemilikCtrl.text,
      'nama_montir': _namaMontirCtrl.text,
      'nomor_polisi': _nomorPolisiCtrl.text,
      'service': _serviceCtrl.text,
      'sparepart': _sparepartCtrl.text,
      'jasa': double.tryParse(_jasaCtrl.text) ?? 0,
      'sparepart_nominal': double.tryParse(_sparepartNominalCtrl.text) ?? 0,
      'total': double.tryParse(_totalCtrl.text) ?? 0,
      'tanggal': DateFormat('yyyy-MM-dd').format(_tanggal ?? DateTime.now()),
    };

    final res = _isEdit
        ? await ApiService.put(
            '$kPenghasilanBase/${widget.item!['id']}', body)
        : await ApiService.post(kPenghasilanBase, body);

    setState(() => _loading = false);

    if (mounted) {
      PushNotificationHelper.show(
        context,
        title: res['success'] == true ? 'Notifikasi Sistem' : 'Peringatan Sistem',
        message: res['success'] == true
            ? (_isEdit ? 'Data penghasilan berhasil diperbarui.' : 'Data penghasilan baru berhasil ditambahkan.')
            : (res['message'] ?? 'Gagal menyimpan data penghasilan.'),
        isSuccess: res['success'] == true,
      );
      if (res['success'] == true) Navigator.pop(context, true);
    }
  }

  @override
  Widget build(BuildContext context) {
    return DraggableScrollableSheet(
      initialChildSize: 0.9,
      maxChildSize: 0.95,
      minChildSize: 0.6,
      builder: (_, controller) => Container(
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
                borderRadius: BorderRadius.circular(2),
              ),
            ),
            Padding(
              padding:
                  const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
              child: Row(
                children: [
                  Text(
                    _isEdit ? 'Edit Penghasilan' : 'Tambah Penghasilan',
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
                controller: controller,
                padding: const EdgeInsets.all(20),
                children: [
                  Form(
                    key: _formKey,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        _field(_namaPemilikCtrl, 'Nama Pemilik',
                            Icons.person_outline,
                            validator: (v) =>
                                v!.isEmpty ? 'Wajib diisi' : null),
                        _field(_namaMontirCtrl, 'Nama Montir',
                            Icons.engineering_outlined),
                        _field(_nomorPolisiCtrl, 'Nomor Polisi',
                            Icons.directions_car_outlined),
                        _field(_serviceCtrl, 'Service / Jenis Layanan',
                            Icons.build_outlined,
                            maxLines: 3),
                        _field(_sparepartCtrl, 'Sparepart',
                            Icons.settings_outlined,
                            maxLines: 2),
                        _field(
                          _jasaCtrl,
                          'Biaya Jasa (Rp)',
                          Icons.handyman_outlined,
                          type: TextInputType.number,
                          onChanged: (_) => _autoCalcTotal(),
                        ),
                        _field(
                          _sparepartNominalCtrl,
                          'Biaya Sparepart (Rp)',
                          Icons.inventory_outlined,
                          type: TextInputType.number,
                          onChanged: (_) => _autoCalcTotal(),
                        ),
                        _field(
                          _totalCtrl,
                          'Total (Rp)',
                          Icons.attach_money_rounded,
                          type: TextInputType.number,
                          validator: (v) =>
                              v!.isEmpty ? 'Wajib diisi' : null,
                        ),
                        const SizedBox(height: 4),
                        _label('Tanggal Transaksi'),
                        const SizedBox(height: 8),
                        GestureDetector(
                          onTap: _pickDate,
                          child: Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 16, vertical: 14),
                            decoration: BoxDecoration(
                              color: AppTheme.bgSecondary,
                              borderRadius: BorderRadius.circular(12),
                              border:
                                  Border.all(color: AppTheme.borderColor),
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
                                        strokeWidth: 2, color: Colors.white),
                                  )
                                : Text(
                                    _isEdit ? 'Perbarui Data' : 'Simpan Data',
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
            primary: AppTheme.primaryColor,
            surface: AppTheme.bgCard,
          ),
        ),
        child: child!,
      ),
    );
    if (picked != null) setState(() => _tanggal = picked);
  }

  Widget _label(String text) => Text(
        text,
        style: GoogleFonts.inter(
            color: AppTheme.textSecondary,
            fontSize: 13,
            fontWeight: FontWeight.w500),
      );

  Widget _field(
    TextEditingController ctrl,
    String label,
    IconData icon, {
    String? Function(String?)? validator,
    TextInputType type = TextInputType.text,
    int maxLines = 1,
    Function(String)? onChanged,
  }) =>
      Padding(
        padding: const EdgeInsets.only(bottom: 16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _label(label),
            const SizedBox(height: 8),
            TextFormField(
              controller: ctrl,
              keyboardType: type,
              maxLines: maxLines,
              style:
                  GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 14),
              decoration: InputDecoration(
                prefixIcon: Icon(icon, size: 18),
                hintText: label,
              ),
              validator: validator,
              onChanged: onChanged,
            ),
          ],
        ),
      );
}
