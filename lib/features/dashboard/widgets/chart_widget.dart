import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../core/theme/app_theme.dart';

class ChartWidget extends StatelessWidget {
  final Map<String, dynamic>? chartData;

  const ChartWidget({super.key, this.chartData});

  @override
  Widget build(BuildContext context) {
    double parseD(dynamic v) {
      if (v == null) return 0.0;
      if (v is num) return v.toDouble();
      if (v is String) return double.tryParse(v) ?? 0.0;
      return 0.0;
    }

    final labels = List<String>.from(chartData?['labels'] ?? []);
    final income = List<double>.from((chartData?['income'] ?? []).map((v) => parseD(v)));
    final expense = List<double>.from((chartData?['expense'] ?? []).map((v) => parseD(v)));

    if (labels.isEmpty) {
      return Container(
        height: 200,
        decoration: BoxDecoration(
          color: AppTheme.bgCard,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: AppTheme.borderColor),
        ),
        child: Center(
          child: Text(
            'Tidak ada data',
            style: GoogleFonts.inter(color: AppTheme.textMuted),
          ),
        ),
      );
    }

    final highestY = [
      ...income,
      ...expense,
    ].fold<double>(0, (a, b) => a > b ? a : b);
    final maxCleanY = highestY > 0 ? highestY * 1.3 : 100.0;
    final maxCleanX = labels.length > 1 ? (labels.length - 1).toDouble() : 1.0;

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.bgCard,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppTheme.borderColor),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Legend
          Row(
            children: [
              _legendDot(AppTheme.accentGreen, 'Penghasilan'),
              const SizedBox(width: 16),
              _legendDot(AppTheme.accentRed, 'Pengeluaran'),
            ],
          ),
          const SizedBox(height: 16),
          SizedBox(
            height: 180,
            child: LineChart(
              LineChartData(
                gridData: FlGridData(
                  show: true,
                  drawVerticalLine: false,
                  horizontalInterval: maxCleanY / 4,
                  getDrawingHorizontalLine: (_) => FlLine(
                    color: AppTheme.borderColor.withOpacity(0.5),
                    strokeWidth: 1,
                    dashArray: [4, 4],
                  ),
                ),
                titlesData: FlTitlesData(
                  leftTitles: AxisTitles(
                    sideTitles: SideTitles(
                      showTitles: true,
                      reservedSize: 40,
                      interval: maxCleanY / 4,
                      getTitlesWidget: (value, meta) => Text(
                        '${(value / 1000).toStringAsFixed(0)}k',
                        style: GoogleFonts.inter(
                            color: AppTheme.textMuted, fontSize: 9),
                      ),
                    ),
                  ),
                  bottomTitles: AxisTitles(
                    sideTitles: SideTitles(
                      showTitles: true,
                      interval: (labels.length / 5).ceilToDouble().clamp(1.0, 99.0),
                      getTitlesWidget: (value, meta) {
                        final idx = value.toInt();
                        if (idx < 0 || idx >= labels.length) {
                          return const SizedBox.shrink();
                        }
                        return Padding(
                          padding: const EdgeInsets.only(top: 4),
                          child: Text(
                            labels[idx],
                            style: GoogleFonts.inter(
                                color: AppTheme.textMuted, fontSize: 9),
                          ),
                        );
                      },
                    ),
                  ),
                  topTitles: const AxisTitles(
                      sideTitles: SideTitles(showTitles: false)),
                  rightTitles: const AxisTitles(
                      sideTitles: SideTitles(showTitles: false)),
                ),
                borderData: FlBorderData(show: false),
                minX: 0,
                maxX: maxCleanX,
                minY: 0,
                maxY: maxCleanY,
                lineBarsData: [
                  _lineBarData(income, AppTheme.accentGreen),
                  _lineBarData(expense, AppTheme.accentRed),
                ],
                lineTouchData: LineTouchData(
                  touchTooltipData: LineTouchTooltipData(
                    getTooltipColor: (_) => AppTheme.bgSecondary,
                    getTooltipItems: (spots) => spots
                        .map((s) => LineTooltipItem(
                              '${s.y.toStringAsFixed(0)}k',
                              GoogleFonts.inter(
                                color: s.bar.color ?? Colors.white,
                                fontSize: 11,
                                fontWeight: FontWeight.w600,
                              ),
                            ))
                        .toList(),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  LineChartBarData _lineBarData(List<double> data, Color color) {
    return LineChartBarData(
      spots: data
          .asMap()
          .entries
          .map((e) => FlSpot(e.key.toDouble(), e.value))
          .toList(),
      isCurved: true,
      color: color,
      barWidth: 2.5,
      isStrokeCapRound: true,
      dotData: const FlDotData(show: false),
      belowBarData: BarAreaData(
        show: true,
        color: color.withOpacity(0.08),
      ),
    );
  }

  Widget _legendDot(Color color, String label) => Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 8,
            height: 8,
            decoration: BoxDecoration(color: color, shape: BoxShape.circle),
          ),
          const SizedBox(width: 6),
          Text(
            label,
            style:
                GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 11),
          ),
        ],
      );
}
