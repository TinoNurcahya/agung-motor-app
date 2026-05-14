import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:shimmer/shimmer.dart';
import 'package:intl/intl.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/theme/app_theme.dart';

class AiScreen extends StatefulWidget {
  const AiScreen({super.key});
  @override
  State<AiScreen> createState() => _AiScreenState();
}

class _AiScreenState extends State<AiScreen> {
  Map<String, dynamic>? _predictions;
  Map<String, dynamic>? _stockHealth;
  Map<String, dynamic>? _retention;
  Map<String, dynamic>? _forecast;
  List<dynamic> _restock = [];
  Map<String, dynamic>? _strategy;
  bool _isLoading = true;
  bool _isRefreshing = false;
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
        ApiService.get(kAiPredictions),
        ApiService.get(kAiStockHealth),
        ApiService.get(kAiCustomerRetention),
        ApiService.get(kAiForecast),
        ApiService.get(kAiRecommendations),
      ]);
      if (mounted) {
        setState(() {
          final pData = results[0]?['data']?['revenue_prediction'] ?? results[0]?['data'] ?? results[0] ?? {};
          final strategyData = results[0]?['data']?['ai_strategy'] ?? {};
          final sData = results[1]?['data'] ?? results[1] ?? {};
          final rData = results[2]?['data'] ?? results[2] ?? {};
          final fData = results[3]?['data'] ?? results[3] ?? {};
          final rec = results[4];

          _predictions = {
            'predicted_revenue': pData['predicted_revenue'] ?? 0,
            'next_month': pData['next_period'] ?? pData['next_month'] ?? '',
            'percentage_change': pData['percentage_change'] ?? 0,
            'growth_rate': pData['trend'] ?? pData['growth_rate'] ?? 0,
            ...strategyData,
          };

          _stockHealth = {
            'urgent_restock': (sData['out_of_stock'] ?? 0) + (sData['low_stock'] ?? 0),
            ...sData,
          };

          _retention = {
            'retention_rate': rData['current_retention_rate'] ?? rData['retention_rate'] ?? 0,
            'status': rData['status'] ?? '',
            ...rData,
          };

          _forecast = fData;

          final rawRec = rec is List ? rec : (rec?['data'] ?? []);
          _restock = rawRec.map((item) => {
            'name': item['name'] ?? item['nama'] ?? '-',
            'kategori': item['category'] ?? item['kategori'] ?? '-',
            'stock': item['current_stock'] ?? item['stock'] ?? item['stok'] ?? 0,
            'risk': item['urgency'] == 'critical' ? 'Kritis' : item['urgency'] == 'high' ? 'Tinggi' : item['urgency'] == 'medium' ? 'Sedang' : 'Rendah',
            'time': item['timeframe'] ?? item['time'] ?? '',
          }).toList();
        });
      }
    } catch (_) {} finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  Future<void> _refresh() async {
    setState(() => _isRefreshing = true);
    try {
      await ApiService.get(kAiRefresh);
      await _fetch();
    } catch (_) {} finally {
      if (mounted) setState(() => _isRefreshing = false);
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
            child: Row(children: [
              Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                Text('AI Analytics', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 20, fontWeight: FontWeight.w700)),
                Text('Prediksi & rekomendasi cerdas', style: GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 13)),
              ])),
              ElevatedButton.icon(
                onPressed: _isRefreshing ? null : _refresh,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.primaryColor.withOpacity(0.15),
                  foregroundColor: AppTheme.primaryColor,
                  elevation: 0,
                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                ),
                icon: _isRefreshing ? const SizedBox(width: 14, height: 14, child: CircularProgressIndicator(strokeWidth: 2, color: AppTheme.primaryColor)) : const Icon(Icons.refresh_rounded, size: 18),
                label: Text('Refresh', style: GoogleFonts.inter(fontSize: 13, fontWeight: FontWeight.w600)),
              ),
            ]),
          )),
          if (_isLoading)
            SliverToBoxAdapter(child: _shimmer())
          else ...[
            // 3 KPI Cards
            SliverToBoxAdapter(child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(children: [
                Row(children: [
                  _AiKpiCard(
                    title: 'Prediksi Omset',
                    value: _predictions != null ? _fmt.format(double.tryParse(_predictions!['predicted_revenue'].toString()) ?? 0) : '-',
                    subtitle: _predictions != null ? _predictions!['next_month']?.toString() ?? '' : '',
                    change: _predictions?['percentage_change'],
                    icon: Icons.insights_rounded,
                    color: AppTheme.primaryColor,
                  ),
                  const SizedBox(width: 12),
                  _AiKpiCard(
                    title: 'Stok Kritis',
                    value: _stockHealth != null ? '${_stockHealth!['urgent_restock']} item' : '-',
                    subtitle: 'Perlu restock segera',
                    change: null,
                    icon: Icons.warning_amber_rounded,
                    color: AppTheme.accentOrange,
                  ),
                ]),
                const SizedBox(height: 12),
                _AiKpiCard(
                  title: 'Retensi Pelanggan',
                  value: _retention != null ? '${_retention!['retention_rate']}%' : '-',
                  subtitle: _retention?['status']?.toString() ?? '',
                  change: null,
                  icon: Icons.people_alt_rounded,
                  color: AppTheme.accentGreen,
                  fullWidth: true,
                ),
              ]),
            )),
            // Forecast Chart
            if (_forecast != null)
              SliverToBoxAdapter(child: _buildForecastChart()),
            // AI Strategy
            if (_predictions != null)
              SliverToBoxAdapter(child: _buildStrategyCard()),
            // Restock Recommendations
            if (_restock.isNotEmpty)
              SliverToBoxAdapter(child: _buildRestockList()),
            const SliverToBoxAdapter(child: SizedBox(height: 100)),
          ],
        ]),
      ),
    );
  }

  Widget _buildForecastChart() {
    final labels = List<String>.from(_forecast!['labels'] ?? []);
    final historical = List<dynamic>.from(_forecast!['historical'] ?? []);
    final forecast = List<dynamic>.from(_forecast!['forecast'] ?? []);
    final trend = double.tryParse(_forecast!['trend'].toString()) ?? 0;

    double parseD(dynamic v) {
      if (v == null) return 0.0;
      if (v is num) return v.toDouble();
      if (v is String) return double.tryParse(v) ?? 0.0;
      return 0.0;
    }

    // Combine into one list
    final combined = [...historical.map((v) => parseD(v)), ...forecast.map((v) => parseD(v))];
    final histLen = historical.length;

    final highestY = combined.fold<double>(0, (a, b) => a > b ? a : b);
    final maxCleanY = highestY > 0 ? highestY * 1.3 : 100.0;
    final maxCleanX = combined.length > 1 ? (combined.length - 1).toDouble() : 1.0;

    List<FlSpot> getHistSpots() {
      if (historical.isEmpty) return [const FlSpot(0, 0)];
      return List.generate(histLen, (i) => FlSpot(i.toDouble(), combined[i]));
    }

    List<FlSpot> getForeSpots() {
      if (forecast.isEmpty) return [const FlSpot(0, 0)];
      final allSpots = List.generate(combined.length, (i) => FlSpot(i.toDouble(), combined[i]));
      return histLen > 0 ? [allSpots[histLen - 1], ...allSpots.sublist(histLen)] : allSpots.sublist(histLen);
    }

    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: AppTheme.bgCard, borderRadius: BorderRadius.circular(16), border: Border.all(color: AppTheme.borderColor)),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
          Text('Forecast Omset 3 Bulan', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.w600)),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
            decoration: BoxDecoration(color: (trend >= 0 ? AppTheme.accentGreen : AppTheme.accentRed).withOpacity(0.15), borderRadius: BorderRadius.circular(20)),
            child: Text('${trend >= 0 ? '+' : ''}${trend.toStringAsFixed(1)}%/bln', style: GoogleFonts.inter(color: trend >= 0 ? AppTheme.accentGreen : AppTheme.accentRed, fontSize: 11, fontWeight: FontWeight.w600)),
          ),
        ]),
        const SizedBox(height: 4),
        Row(children: [
          _Legend(color: AppTheme.primaryColor, label: 'Historis'),
          const SizedBox(width: 12),
          _Legend(color: AppTheme.accentOrange, label: 'Prediksi', dashed: true),
        ]),
        const SizedBox(height: 16),
        SizedBox(height: 180, child: LineChart(LineChartData(
          gridData: FlGridData(show: true, drawVerticalLine: false, horizontalInterval: maxCleanY / 4, getDrawingHorizontalLine: (_) => FlLine(color: AppTheme.borderColor, strokeWidth: 0.5)),
          titlesData: FlTitlesData(
            leftTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
            rightTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
            topTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
            bottomTitles: AxisTitles(sideTitles: SideTitles(
              showTitles: true, interval: 1,
              getTitlesWidget: (v, _) {
                final idx = v.toInt();
                if (idx >= 0 && idx < labels.length) return Padding(padding: const EdgeInsets.only(top: 4), child: Text(labels[idx], style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 8)));
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
            LineChartBarData(spots: getHistSpots(), color: AppTheme.primaryColor, barWidth: 2.5, dotData: const FlDotData(show: false), belowBarData: BarAreaData(show: true, color: AppTheme.primaryColor.withOpacity(0.08))),
            LineChartBarData(spots: getForeSpots(), color: AppTheme.accentOrange, barWidth: 2.5, dashArray: [6, 4], dotData: FlDotData(show: true, getDotPainter: (_, __, ___, ____) => FlDotCirclePainter(radius: 3, color: AppTheme.accentOrange))),
          ],
        ))),
      ]),
    );
  }

  Widget _buildStrategyCard() {
    final pred = _predictions!;
    final growthRate = double.tryParse(pred['growth_rate'].toString()) ?? 0;
    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        gradient: LinearGradient(colors: [AppTheme.primaryColor.withOpacity(0.15), AppTheme.primaryDark.withOpacity(0.08)], begin: Alignment.topLeft, end: Alignment.bottomRight),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppTheme.primaryColor.withOpacity(0.3)),
      ),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [
          Container(padding: const EdgeInsets.all(8), decoration: BoxDecoration(color: AppTheme.primaryColor.withOpacity(0.15), borderRadius: BorderRadius.circular(10)), child: const Icon(Icons.psychology_rounded, color: AppTheme.primaryColor, size: 22)),
          const SizedBox(width: 12),
          Text('Strategi AI', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.w600)),
        ]),
        const SizedBox(height: 12),
        Text(
          'Rata-rata pertumbuhan omset: ${growthRate.toStringAsFixed(1)}% per bulan. '
          'Stok kritis yang perlu segera direstock: ${_stockHealth?['urgent_restock'] ?? 0} item. '
          'Tingkat retensi pelanggan saat ini ${_retention?['retention_rate'] ?? 0}% (${_retention?['status'] ?? '-'}). '
          'Optimalkan layanan di minggu pertama dan tengah bulan untuk memaksimalkan pendapatan.',
          style: GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 13, height: 1.5),
        ),
      ]),
    );
  }

  Widget _buildRestockList() {
    return Container(
      margin: const EdgeInsets.fromLTRB(16, 16, 16, 0),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: AppTheme.bgCard, borderRadius: BorderRadius.circular(16), border: Border.all(color: AppTheme.borderColor)),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
          Text('Rekomendasi Restock', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.w600)),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
            decoration: BoxDecoration(color: AppTheme.accentOrange.withOpacity(0.15), borderRadius: BorderRadius.circular(20)),
            child: Text('${_restock.length} item', style: GoogleFonts.inter(color: AppTheme.accentOrange, fontSize: 11, fontWeight: FontWeight.w600)),
          ),
        ]),
        const SizedBox(height: 16),
        ..._restock.take(5).map((r) {
          final risk = r['risk'] ?? 'Rendah';
          final Color riskColor = risk == 'Kritis' ? AppTheme.accentRed : risk == 'Tinggi' ? AppTheme.accentOrange : risk == 'Sedang' ? const Color(0xFFF59E0B) : AppTheme.accentGreen;
          final stok = r['stock'] ?? 0;
          final double pct = stok <= 0 ? 1.0 : (stok / 20.0).clamp(0.0, 1.0).toDouble();
          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(color: AppTheme.bgSecondary, borderRadius: BorderRadius.circular(12), border: Border.all(color: riskColor.withOpacity(0.2))),
            child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Row(children: [
                Expanded(child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  Text(r['name'] ?? '-', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 13, fontWeight: FontWeight.w600), maxLines: 1, overflow: TextOverflow.ellipsis),
                  Text('${r['kategori'] ?? '-'} · Stok: $stok · ${r['time'] ?? ''}', style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 11)),
                ])),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                  decoration: BoxDecoration(color: riskColor.withOpacity(0.15), borderRadius: BorderRadius.circular(12)),
                  child: Text(risk, style: GoogleFonts.inter(color: riskColor, fontSize: 11, fontWeight: FontWeight.w600)),
                ),
              ]),
              const SizedBox(height: 8),
              ClipRRect(
                borderRadius: BorderRadius.circular(4),
                child: LinearProgressIndicator(value: 1 - pct, backgroundColor: AppTheme.bgPrimary, valueColor: AlwaysStoppedAnimation<Color>(riskColor), minHeight: 5),
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
        Expanded(child: Container(height: 100, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12)))),
        const SizedBox(width: 12),
        Expanded(child: Container(height: 100, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12)))),
      ]),
      const SizedBox(height: 12),
      Container(height: 220, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16))),
      const SizedBox(height: 12),
      Container(height: 140, decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16))),
    ])),
  );
}

class _AiKpiCard extends StatelessWidget {
  final String title, value, subtitle;
  final dynamic change;
  final IconData icon;
  final Color color;
  final bool fullWidth;
  const _AiKpiCard({required this.title, required this.value, required this.subtitle, this.change, required this.icon, required this.color, this.fullWidth = false});

  @override
  Widget build(BuildContext context) {
    final ch = change != null ? double.tryParse(change.toString()) : null;
    final card = Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(color: AppTheme.bgCard, borderRadius: BorderRadius.circular(14), border: Border.all(color: AppTheme.borderColor)),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
          Container(padding: const EdgeInsets.all(8), decoration: BoxDecoration(color: color.withOpacity(0.12), borderRadius: BorderRadius.circular(10)), child: Icon(icon, color: color, size: 18)),
          if (ch != null)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
              decoration: BoxDecoration(color: (ch >= 0 ? AppTheme.accentGreen : AppTheme.accentRed).withOpacity(0.15), borderRadius: BorderRadius.circular(20)),
              child: Text('${ch >= 0 ? '+' : ''}${ch.toStringAsFixed(1)}%', style: GoogleFonts.inter(color: ch >= 0 ? AppTheme.accentGreen : AppTheme.accentRed, fontSize: 10, fontWeight: FontWeight.w600)),
            ),
        ]),
        const SizedBox(height: 10),
        Text(value, style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 16, fontWeight: FontWeight.w700), maxLines: 1, overflow: TextOverflow.ellipsis),
        const SizedBox(height: 2),
        Text(title, style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 11)),
        if (subtitle.isNotEmpty) ...[
          const SizedBox(height: 2),
          Text(subtitle, style: GoogleFonts.inter(color: color, fontSize: 11, fontWeight: FontWeight.w500)),
        ],
      ]),
    );
    if (fullWidth) return card;
    return Expanded(child: card);
  }
}

class _Legend extends StatelessWidget {
  final Color color;
  final String label;
  final bool dashed;
  const _Legend({required this.color, required this.label, this.dashed = false});
  @override
  Widget build(BuildContext context) => Row(children: [
    Container(width: 14, height: 3, decoration: BoxDecoration(color: color, borderRadius: BorderRadius.circular(2))),
    const SizedBox(width: 6),
    Text(label, style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 11)),
  ]);
}
