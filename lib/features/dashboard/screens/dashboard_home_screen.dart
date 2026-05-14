import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:shimmer/shimmer.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/theme/app_theme.dart';
import '../widgets/stat_card.dart';
import '../widgets/chart_widget.dart';
import '../widgets/recent_transactions_widget.dart';

class DashboardHomeScreen extends StatefulWidget {
  const DashboardHomeScreen({super.key});

  @override
  State<DashboardHomeScreen> createState() => _DashboardHomeScreenState();
}

class _DashboardHomeScreenState extends State<DashboardHomeScreen> {
  Map<String, dynamic>? _overview;
  Map<String, dynamic>? _chart;
  List<dynamic> _recentTx = [];
  bool _loading = true;
  String _error = '';
  int _period = 30;
  double _usdRate = 16250.0;
  bool _loadingRate = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() {
      _loading = true;
      _loadingRate = true;
    });

    try {
      final rateRes = await http.get(Uri.parse('https://open.er-api.com/v6/latest/USD')).timeout(const Duration(seconds: 5));
      if (rateRes.statusCode == 200) {
        final data = jsonDecode(rateRes.body);
        if (data['rates'] != null && data['rates']['IDR'] != null) {
          _usdRate = (data['rates']['IDR'] as num).toDouble();
        }
      }
    } catch (_) {}

    final overviewRes =
        await ApiService.get('$kDashboardOverview?period=$_period');
    final chartRes = await ApiService.get('$kDashboardChart?period=$_period');
    final recentRes = await ApiService.get('$kDashboardRecent?limit=5');

    if (mounted) {
      setState(() {
        _loading = false;
        _loadingRate = false;
        if (overviewRes['success'] == true) {
          _overview = overviewRes['data'] ?? overviewRes;
        }
        if (chartRes['success'] == true) {
          _chart = chartRes['data'] ?? chartRes;
        }
        if (recentRes['success'] == true) {
          _recentTx = recentRes['data'] ?? (recentRes is List ? recentRes : []);
        }
      });
    }
  }

  double _parseDouble(dynamic value) {
    if (value == null) return 0.0;
    if (value is num) return value.toDouble();
    if (value is String) return double.tryParse(value) ?? 0.0;
    return 0.0;
  }

  String _formatRupiah(dynamic value) {
    final num = _parseDouble(value);
    return 'Rp ${NumberFormat('#,###', 'id_ID').format(num)}';
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      color: AppTheme.primaryColor,
      backgroundColor: AppTheme.bgCard,
      onRefresh: _loadData,
      child: CustomScrollView(
        slivers: [
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Header
                  _buildHeader(),
                  const SizedBox(height: 20),

                  // Period Filter
                  _buildPeriodFilter(),
                  const SizedBox(height: 20),

                  // Stats Cards
                  if (_loading) _buildShimmerCards() else _buildStatCards(),
                  const SizedBox(height: 20),

                  // Chart
                  _buildSectionTitle('Grafik Keuangan'),
                  const SizedBox(height: 12),
                  if (_loading)
                    _buildShimmerChart()
                  else
                    ChartWidget(chartData: _chart),
                  const SizedBox(height: 20),

                  // Recent Transactions
                  _buildSectionTitle('Transaksi Terakhir'),
                  const SizedBox(height: 12),
                  if (_loading)
                    _buildShimmerList()
                  else
                    RecentTransactionsWidget(transactions: _recentTx),
                  const SizedBox(height: 24),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader() {
    final now = DateFormat('EEEE, d MMMM yyyy', 'id_ID').format(DateTime.now());
    return Row(
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Selamat Datang! 👋',
                style: GoogleFonts.inter(
                    color: AppTheme.textSecondary, fontSize: 13),
              ),
              const SizedBox(height: 4),
              Text(
                'Dashboard Admin',
                style: GoogleFonts.inter(
                  color: AppTheme.textPrimary,
                  fontSize: 22,
                  fontWeight: FontWeight.w700,
                ),
              ),
              Text(
                now,
                style: GoogleFonts.inter(
                    color: AppTheme.textMuted, fontSize: 12),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildPeriodFilter() {
    final periods = [7, 30, 90];
    final labels = ['7 Hari', '30 Hari', '90 Hari'];
    return Row(
      children: List.generate(
        periods.length,
        (i) => Padding(
          padding: EdgeInsets.only(right: i < periods.length - 1 ? 8 : 0),
          child: GestureDetector(
            onTap: () {
              setState(() => _period = periods[i]);
              _loadData();
            },
            child: AnimatedContainer(
              duration: const Duration(milliseconds: 200),
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              decoration: BoxDecoration(
                color: _period == periods[i]
                    ? AppTheme.primaryColor
                    : AppTheme.bgCard,
                borderRadius: BorderRadius.circular(10),
                border: Border.all(
                  color: _period == periods[i]
                      ? AppTheme.primaryColor
                      : AppTheme.borderColor,
                ),
              ),
              child: Text(
                labels[i],
                style: GoogleFonts.inter(
                  color: _period == periods[i]
                      ? Colors.white
                      : AppTheme.textSecondary,
                  fontSize: 13,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildStatCards() {
    final penghasilan = _overview?['total_penghasilan'] ?? 0;
    final pengeluaran = _overview?['total_pengeluaran'] ?? 0;
    final laba = _overview?['laba_bersih'] ?? 0;
    final phChange = _parseDouble(_overview?['penghasilan_change']);
    final plChange = _parseDouble(_overview?['pengeluaran_change']);

    return Column(
      children: [
        Row(
          children: [
            Expanded(
              child: StatCard(
                title: 'Total Penghasilan',
                value: _formatRupiah(penghasilan),
                icon: Icons.trending_up_rounded,
                iconColor: AppTheme.accentGreen,
                bgColor: AppTheme.accentGreen.withOpacity(0.1),
                change: phChange,
                period: '$_period hari',
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: StatCard(
                title: 'Total Pengeluaran',
                value: _formatRupiah(pengeluaran),
                icon: Icons.trending_down_rounded,
                iconColor: AppTheme.accentRed,
                bgColor: AppTheme.accentRed.withOpacity(0.1),
                change: plChange,
                period: '$_period hari',
                isNegative: true,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        StatCard(
          title: 'Laba Bersih',
          value: _formatRupiah(laba),
          icon: Icons.account_balance_wallet_rounded,
          iconColor: AppTheme.primaryColor,
          bgColor: AppTheme.primaryColor.withOpacity(0.1),
          isFullWidth: true,
          period: '$_period hari',
        ),
        const SizedBox(height: 12),
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: AppTheme.bgCard,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(color: AppTheme.borderColor),
          ),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: AppTheme.accentGreen.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(Icons.currency_exchange_rounded, color: AppTheme.accentGreen, size: 24),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Nilai Tukar Rupiah ke Dolar',
                      style: GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 12),
                    ),
                    const SizedBox(height: 4),
                    _loadingRate
                        ? SizedBox(height: 20, width: 80, child: LinearProgressIndicator(backgroundColor: AppTheme.bgSecondary, valueColor: AlwaysStoppedAnimation(AppTheme.accentGreen)))
                        : Text(
                            'Rp ${NumberFormat('#,###', 'id_ID').format(_usdRate)} / \$1',
                            style: GoogleFonts.inter(
                              color: AppTheme.textPrimary,
                              fontSize: 18,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                    const SizedBox(height: 2),
                    Text(
                      'Setara Laba: \$${NumberFormat('#,###.00', 'en_US').format(_parseDouble(laba) / _usdRate)} USD',
                      style: GoogleFonts.inter(color: AppTheme.accentGreen, fontSize: 11, fontWeight: FontWeight.w600),
                    ),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: AppTheme.accentGreen.withOpacity(0.15),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  'Real-Time',
                  style: GoogleFonts.inter(color: AppTheme.accentGreen, fontSize: 10, fontWeight: FontWeight.w600),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildSectionTitle(String title) => Text(
        title,
        style: GoogleFonts.inter(
          color: AppTheme.textPrimary,
          fontSize: 16,
          fontWeight: FontWeight.w600,
        ),
      );

  Widget _buildShimmerCards() => Shimmer.fromColors(
        baseColor: AppTheme.bgCard,
        highlightColor: AppTheme.bgCardHover,
        child: Column(
          children: [
            Row(
              children: [
                Expanded(child: _shimmerBox(120)),
                const SizedBox(width: 12),
                Expanded(child: _shimmerBox(120)),
              ],
            ),
            const SizedBox(height: 12),
            _shimmerBox(80),
          ],
        ),
      );

  Widget _buildShimmerChart() => Shimmer.fromColors(
        baseColor: AppTheme.bgCard,
        highlightColor: AppTheme.bgCardHover,
        child: _shimmerBox(200),
      );

  Widget _buildShimmerList() => Shimmer.fromColors(
        baseColor: AppTheme.bgCard,
        highlightColor: AppTheme.bgCardHover,
        child: Column(
          children: List.generate(
              4,
              (i) => Padding(
                    padding: const EdgeInsets.only(bottom: 8),
                    child: _shimmerBox(64),
                  )),
        ),
      );

  Widget _shimmerBox(double height) => Container(
        height: height,
        width: double.infinity,
        decoration: BoxDecoration(
          color: AppTheme.bgCard,
          borderRadius: BorderRadius.circular(16),
        ),
      );
}
