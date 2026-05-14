import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:shimmer/shimmer.dart';
import 'package:intl/intl.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/theme/app_theme.dart';

class StatistikScreen extends StatefulWidget {
  const StatistikScreen({super.key});
  @override
  State<StatistikScreen> createState() => _StatistikScreenState();
}

class _StatistikScreenState extends State<StatistikScreen> {
  Map<String, dynamic>? _summary;
  Map<String, dynamic>? _trend;
  Map<String, dynamic>? _chart;
  List<dynamic> _topServices = [];
  bool _isLoading = true;
  int _period = 30;
  final _fmt = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  @override
  void initState() {
    super.initState();
    _fetch();
  }

  Future<void> _fetch() async {
    setState(() => _isLoading = true);
    try {
      final results = await Future.wait([
        ApiService.get('$kStatistikSummary?period=$_period'),
        ApiService.get('$kStatistikTrend?period=$_period'),
        ApiService.get('$kStatistikChart?period=$_period'),
      ]);
      if (mounted) {
        setState(() {
          _summary = results[0];
          _trend = results[1];
          _chart = results[2];
          _topServices = _summary?['top_services'] ?? [];
        });
      }
    } catch (_) {} finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

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
              Text('Statistik', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 20, fontWeight: FontWeight.w700)),
              const SizedBox(height: 4),
              Text('Analisis keuangan & performa bengkel', style: GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 13)),
              const SizedBox(height: 16),
              // Period selector
              Row(children: [7, 30, 90].map((d) {
                final sel = _period == d;
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: GestureDetector(
                    onTap: () { setState(() => _period = d); _fetch(); },
                    child: AnimatedContainer(
                      duration: const Duration(milliseconds: 200),
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                      decoration: BoxDecoration(
                        color: sel ? AppTheme.primaryColor : AppTheme.bgSecondary,
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: sel ? AppTheme.primaryColor : AppTheme.borderColor),
                      ),
                      child: Text('${d}H', style: GoogleFonts.inter(color: sel ? Colors.white : AppTheme.textSecondary, fontWeight: FontWeight.w600, fontSize: 13)),
                    ),
                  ),
                );
              }).toList()),
            ]),
          )),
          if (_isLoading)
            SliverToBoxAdapter(child: _shimmer())
          else ...[
            // Summary cards
            if (_summary != null)
              SliverToBoxAdapter(child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Column(children: [
                  Row(children: [
                    _SummaryCard(
                      title: 'Total Penghasilan',
                      value: _fmt.format(double.tryParse(_summary!['total_penghasilan'].toString()) ?? 0),
                      icon: Icons.trending_up_rounded,
                      color: AppTheme.accentGreen,
                      change: _summary!['penghasilan_change'],
                    ),
                    const SizedBox(width: 12),
                    _SummaryCard(
                      title: 'Total Pengeluaran',
                      value: _fmt.format(double.tryParse(_summary!['total_pengeluaran'].toString()) ?? 0),
                      icon: Icons.trending_down_rounded,
                      color: AppTheme.accentRed,
                      change: _summary!['pengeluaran_change'],
                    ),
                  ]),
                  const SizedBox(height: 12),
                  Row(children: [
                    _SummaryCard(
                      title: 'Laba Bersih',
                      value: _fmt.format(double.tryParse(_summary!['laba_bersih'].toString()) ?? 0),
                      icon: Icons.account_balance_wallet_rounded,
                      color: AppTheme.accentBlue,
                      change: _summary!['laba_change'],
                    ),
                    const SizedBox(width: 12),
                    _SummaryCard(
                      title: 'Margin Profit',
                      value: '${(double.tryParse(_summary!['margin_profit'].toString()) ?? 0).toStringAsFixed(1)}%',
                      icon: Icons.pie_chart_rounded,
                      color: AppTheme.primaryColor,
                      change: null,
                    ),
                  ]),
                  const SizedBox(height: 20),
                ]),
              )),
            // Income vs Expense Line Chart
            if (_trend != null)
              SliverToBoxAdapter(child: _buildLineChart()),
            // Top Services
            if (_topServices.isNotEmpty)
              SliverToBoxAdapter(child: _buildTopServices()),
            const SliverToBoxAdapter(child: SizedBox(height: 100)),
          ],
        ]),
      ),
    );
  }

  Widget _buildLineChart() {
    final labels = List<String>.from(_trend!['labels'] ?? []);
    final income = List<dynamic>.from(_trend!['income'] ?? []);
    final expense = List<dynamic>.from(_trend!['expense'] ?? []);
    final step = (labels.length / 5).ceil().clamp(1, 99);

    double parseD(dynamic v) {
      if (v == null) return 0.0;
      if (v is num) return v.toDouble();
      if (v is String) return double.tryParse(v) ?? 0.0;
      return 0.0;
    }

    List<FlSpot> toSpots(List<dynamic> data) {
      if (data.isEmpty) return [const FlSpot(0, 0)];
      return List.generate(data.length, (i) => FlSpot(i.toDouble(), parseD(data[i])));
    }

    final incomeDoubles = income.map((v) => parseD(v)).toList();
    final expenseDoubles = expense.map((v) => parseD(v)).toList();
    final highestY = [...incomeDoubles, ...expenseDoubles].fold<double>(0, (a, b) => a > b ? a : b);
    final maxCleanY = highestY > 0 ? highestY * 1.3 : 100.0;
    final maxCleanX = labels.length > 1 ? (labels.length - 1).toDouble() : 1.0;

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: AppTheme.bgCard, borderRadius: BorderRadius.circular(16), border: Border.all(color: AppTheme.borderColor)),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Text('Trend Keuangan', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.w600)),
        const SizedBox(height: 4),
        Row(children: [
          _Legend(color: AppTheme.accentGreen, label: 'Penghasilan'),
          const SizedBox(width: 16),
          _Legend(color: AppTheme.accentRed, label: 'Pengeluaran'),
        ]),
        const SizedBox(height: 16),
        SizedBox(height: 180, child: LineChart(LineChartData(
          gridData: FlGridData(show: true, drawVerticalLine: false, horizontalInterval: maxCleanY / 4, getDrawingHorizontalLine: (_) => FlLine(color: AppTheme.borderColor, strokeWidth: 0.5)),
          titlesData: FlTitlesData(
            leftTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
            rightTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
            topTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
            bottomTitles: AxisTitles(sideTitles: SideTitles(
              showTitles: true,
              interval: step.toDouble(),
              getTitlesWidget: (v, _) {
                final idx = v.toInt();
                if (idx >= 0 && idx < labels.length) return Padding(padding: const EdgeInsets.only(top: 4), child: Text(labels[idx], style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 9)));
                return const Text('');
              },
            )),
          ),
          borderData: FlBorderData(show: false),
          minX: 0,
          maxX: maxCleanX,
          minY: 0,
          maxY: maxCleanY,
          lineBarsData: [
            LineChartBarData(spots: toSpots(income), color: AppTheme.accentGreen, barWidth: 2.5, dotData: const FlDotData(show: false), belowBarData: BarAreaData(show: true, color: AppTheme.accentGreen.withOpacity(0.08))),
            LineChartBarData(spots: toSpots(expense), color: AppTheme.accentRed, barWidth: 2.5, dotData: const FlDotData(show: false), belowBarData: BarAreaData(show: true, color: AppTheme.accentRed.withOpacity(0.08))),
          ],
        ))),
      ]),
    );
  }

  Widget _buildTopServices() {
    double parseD(dynamic v) {
      if (v == null) return 0.0;
      if (v is num) return v.toDouble();
      if (v is String) return double.tryParse(v) ?? 0.0;
      return 0.0;
    }

    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: AppTheme.bgCard, borderRadius: BorderRadius.circular(16), border: Border.all(color: AppTheme.borderColor)),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Text('Top Layanan', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.w600)),
        const SizedBox(height: 16),
        ..._topServices.take(6).map((s) {
          final pct = parseD(s['percentage'] ?? 50);
          return Padding(
            padding: const EdgeInsets.only(bottom: 12),
            child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                Expanded(child: Text(s['name'] ?? '-', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis)),
                Text('${s['count']} transaksi', style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 12)),
              ]),
              const SizedBox(height: 6),
              ClipRRect(
                borderRadius: BorderRadius.circular(4),
                child: LinearProgressIndicator(
                  value: pct / 100,
                  backgroundColor: AppTheme.bgSecondary,
                  valueColor: const AlwaysStoppedAnimation<Color>(AppTheme.primaryColor),
                  minHeight: 6,
                ),
              ),
            ]),
          );
        }),
      ]),
    );
  }

  Widget _shimmer() => Shimmer.fromColors(
    baseColor: AppTheme.bgSecondary,
    highlightColor: AppTheme.bgCardHover,
    child: Padding(padding: const EdgeInsets.all(16), child: Column(children: [
      Row(children: [
        Expanded(child: Container(height: 90, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12)))),
        const SizedBox(width: 12),
        Expanded(child: Container(height: 90, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12)))),
      ]),
      const SizedBox(height: 12),
      Container(height: 220, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16))),
    ])),
  );
}

class _SummaryCard extends StatelessWidget {
  final String title, value;
  final IconData icon;
  final Color color;
  final dynamic change;
  const _SummaryCard({required this.title, required this.value, required this.icon, required this.color, this.change});

  @override
  Widget build(BuildContext context) {
    final ch = change != null ? double.tryParse(change.toString()) : null;
    return Expanded(child: Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(color: AppTheme.bgCard, borderRadius: BorderRadius.circular(14), border: Border.all(color: AppTheme.borderColor)),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
          Icon(icon, color: color, size: 20),
          if (ch != null)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
              decoration: BoxDecoration(color: (ch >= 0 ? AppTheme.accentGreen : AppTheme.accentRed).withOpacity(0.15), borderRadius: BorderRadius.circular(20)),
              child: Text('${ch >= 0 ? '+' : ''}${ch.toStringAsFixed(1)}%', style: GoogleFonts.inter(color: ch >= 0 ? AppTheme.accentGreen : AppTheme.accentRed, fontSize: 10, fontWeight: FontWeight.w600)),
            ),
        ]),
        const SizedBox(height: 8),
        Text(value, style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 14, fontWeight: FontWeight.w700), maxLines: 1, overflow: TextOverflow.ellipsis),
        const SizedBox(height: 2),
        Text(title, style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 11)),
      ]),
    ));
  }
}

class _Legend extends StatelessWidget {
  final Color color;
  final String label;
  const _Legend({required this.color, required this.label});
  @override
  Widget build(BuildContext context) => Row(children: [
    Container(width: 12, height: 3, decoration: BoxDecoration(color: color, borderRadius: BorderRadius.circular(2))),
    const SizedBox(width: 6),
    Text(label, style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 11)),
  ]);
}
