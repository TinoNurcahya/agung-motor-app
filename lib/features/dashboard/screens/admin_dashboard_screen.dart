import 'dart:async';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/services/api_service.dart';
import '../../../core/services/push_notification_helper.dart';
import '../../../core/providers/auth_provider.dart';
import '../../../core/providers/theme_provider.dart';
import '../../../core/theme/app_theme.dart';
import '../../dashboard/screens/dashboard_home_screen.dart';
import '../../penghasilan/screens/penghasilan_screen.dart';
import '../../pengeluaran/screens/pengeluaran_screen.dart';
import '../../produk/screens/produk_screen.dart';
import '../../statistik/screens/statistik_screen.dart';
import '../../ai/screens/ai_screen.dart';

class AdminDashboardScreen extends StatefulWidget {
  const AdminDashboardScreen({super.key});

  @override
  State<AdminDashboardScreen> createState() => _AdminDashboardScreenState();
}

class _AdminDashboardScreenState extends State<AdminDashboardScreen> {
  int _selectedIndex = 0;
  Timer? _pollingTimer;
  final Set<String> _seenNotificationIds = {};

  @override
  void initState() {
    super.initState();
    _initNotifications();
  }

  @override
  void dispose() {
    _pollingTimer?.cancel();
    super.dispose();
  }

  Future<void> _initNotifications() async {
    try {
      final res = await ApiService.get('$kApiBase/dashboard/notifications');
      if (res != null && res['success'] == true) {
        final list = res['data'] as List? ?? [];
        for (final item in list) {
          if (item['id'] != null) {
            _seenNotificationIds.add(item['id'].toString());
          }
        }
      }
    } catch (_) {}

    _pollingTimer = Timer.periodic(const Duration(seconds: 5), (_) {
      _checkForWebUpdates();
    });
  }

  Future<void> _checkForWebUpdates() async {
    if (!mounted) return;
    try {
      final res = await ApiService.get('$kApiBase/dashboard/notifications');
      if (res != null && res['success'] == true && mounted) {
        final list = res['data'] as List? ?? [];
        for (final item in list) {
          final id = item['id']?.toString();
          if (id != null && !_seenNotificationIds.contains(id)) {
            _seenNotificationIds.add(id);
            if (mounted) {
              PushNotificationHelper.show(
                context,
                title: item['title'] ?? 'Pemberitahuan Sistem',
                message: item['message'] ?? '',
                isSuccess: item['type'] == 'success' || item['type'] == 'info',
                onAction: () {
                  final type = item['type'] ?? 'info';
                  if (type == 'success') {
                    setState(() => _selectedIndex = 1);
                  } else if (type == 'info') {
                    setState(() => _selectedIndex = 2);
                  } else {
                    setState(() => _selectedIndex = 3);
                  }
                },
              );
            }
          }
        }
      }
    } catch (_) {}
  }

  final List<_NavItem> _navItems = [
    _NavItem(Icons.dashboard_rounded, 'Dashboard'),
    _NavItem(Icons.trending_up_rounded, 'Penghasilan'),
    _NavItem(Icons.trending_down_rounded, 'Pengeluaran'),
    _NavItem(Icons.inventory_2_rounded, 'Produk'),
    _NavItem(Icons.bar_chart_rounded, 'Statistik'),
    _NavItem(Icons.psychology_rounded, 'AI Analytics'),
  ];

  final List<Widget> _screens = [
    const DashboardHomeScreen(),
    const PenghasilanScreen(),
    const PengeluaranScreen(),
    const ProdukScreen(),
    const StatistikScreen(),
    const AiScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    return Scaffold(
      backgroundColor: AppTheme.bgPrimary,
      appBar: AppBar(
        backgroundColor: AppTheme.bgSecondary,
        elevation: 0,
        title: Image.asset(
          'assets/images/logo.png',
          height: 40,
          fit: BoxFit.contain,
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.notifications_outlined, color: AppTheme.textSecondary),
            onPressed: () => _showNotifications(context),
          ),
          IconButton(
            icon: Icon(context.watch<ThemeProvider>().isDarkMode ? Icons.light_mode_outlined : Icons.dark_mode_outlined, color: AppTheme.textSecondary),
            onPressed: () => context.read<ThemeProvider>().toggleTheme(),
          ),
          GestureDetector(
            onTap: () => _showProfileMenu(context, auth),
            child: Container(
              margin: const EdgeInsets.only(right: 16),
              width: 36,
              height: 36,
              decoration: BoxDecoration(
                color: AppTheme.primaryColor.withOpacity(0.15),
                borderRadius: BorderRadius.circular(10),
                border: Border.all(
                    color: AppTheme.primaryColor.withOpacity(0.3)),
              ),
              child: const Icon(Icons.person_outline,
                  color: AppTheme.primaryColor, size: 20),
            ),
          ),
        ],
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(1),
          child: Container(height: 1, color: AppTheme.borderColor),
        ),
      ),
      body: _screens[_selectedIndex],
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: AppTheme.bgSecondary,
          border: Border(top: BorderSide(color: AppTheme.borderColor)),
        ),
        child: SafeArea(
          child: SizedBox(
            height: 64,
            child: Row(
              children: List.generate(
                _navItems.length,
                (i) => _buildNavItem(i),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildNavItem(int index) {
    final item = _navItems[index];
    final isSelected = _selectedIndex == index;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _selectedIndex = index),
        behavior: HitTestBehavior.opaque,
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 200),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              AnimatedContainer(
                duration: const Duration(milliseconds: 200),
                padding: const EdgeInsets.all(6),
                decoration: BoxDecoration(
                  color: isSelected
                      ? AppTheme.primaryColor.withOpacity(0.15)
                      : Colors.transparent,
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Icon(
                  item.icon,
                  size: 22,
                  color: isSelected
                      ? AppTheme.primaryColor
                      : AppTheme.textMuted,
                ),
              ),
              const SizedBox(height: 2),
              Text(
                item.label,
                style: GoogleFonts.inter(
                  fontSize: 9,
                  fontWeight:
                      isSelected ? FontWeight.w600 : FontWeight.w400,
                  color: isSelected
                      ? AppTheme.primaryColor
                      : AppTheme.textMuted,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _showProfileMenu(BuildContext context, AuthProvider auth) {
    showModalBottomSheet(
      context: context,
      backgroundColor: AppTheme.bgCard,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (_) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 48,
              height: 48,
              decoration: BoxDecoration(
                color: AppTheme.primaryColor.withOpacity(0.15),
                shape: BoxShape.circle,
                border: Border.all(color: AppTheme.primaryColor.withOpacity(0.3)),
              ),
              child: const Icon(Icons.person, color: AppTheme.primaryColor),
            ),
            const SizedBox(height: 12),
            Text(
              auth.userName,
              style: GoogleFonts.inter(
                  color: AppTheme.textPrimary,
                  fontSize: 16,
                  fontWeight: FontWeight.w600),
            ),
            Text(
              auth.userEmail,
              style: GoogleFonts.inter(
                  color: AppTheme.textSecondary, fontSize: 13),
            ),
            const SizedBox(height: 4),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
              decoration: BoxDecoration(
                color: AppTheme.primaryColor.withOpacity(0.15),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text(
                'Admin',
                style: GoogleFonts.inter(
                    color: AppTheme.primaryColor,
                    fontSize: 12,
                    fontWeight: FontWeight.w600),
              ),
            ),
            const SizedBox(height: 24),
            Divider(color: AppTheme.borderColor),
            const SizedBox(height: 8),
            ListTile(
              leading: Icon(context.watch<ThemeProvider>().isDarkMode ? Icons.light_mode_outlined : Icons.dark_mode_outlined, color: AppTheme.primaryColor),
              title: Text(
                context.watch<ThemeProvider>().isDarkMode ? 'Mode Terang' : 'Mode Gelap',
                style: GoogleFonts.inter(color: AppTheme.primaryColor, fontWeight: FontWeight.w500),
              ),
              onTap: () {
                context.read<ThemeProvider>().toggleTheme();
              },
            ),
            ListTile(
              leading: const Icon(Icons.logout_rounded, color: AppTheme.accentRed),
              title: Text(
                'Keluar',
                style: GoogleFonts.inter(
                    color: AppTheme.accentRed, fontWeight: FontWeight.w500),
              ),
              onTap: () async {
                Navigator.pop(context);
                await auth.logout();
              },
            ),
          ],
        ),
      ),
    );
  }

  void _showNotifications(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: AppTheme.bgCard,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (_) => _NotificationsSheet(
        onSelectTab: (idx) {
          setState(() => _selectedIndex = idx);
        },
      ),
    );
  }
}

class _NotificationsSheet extends StatefulWidget {
  final void Function(int) onSelectTab;
  const _NotificationsSheet({required this.onSelectTab});
  @override
  State<_NotificationsSheet> createState() => _NotificationsSheetState();
}

class _NotificationsSheetState extends State<_NotificationsSheet> {
  List<dynamic> _items = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _fetch();
  }

  Future<void> _fetch() async {
    try {
      final res = await ApiService.get('$kApiBase/dashboard/notifications');
      if (res != null && res['success'] == true && mounted) {
        setState(() {
          _items = res['data'] ?? [];
          _loading = false;
        });
      }
    } catch (_) {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      constraints: BoxConstraints(maxHeight: MediaQuery.of(context).size.height * 0.75),
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Notifikasi Sistem', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontSize: 18, fontWeight: FontWeight.w700)),
              IconButton(icon: Icon(Icons.close, color: AppTheme.textSecondary), onPressed: () => Navigator.pop(context)),
            ],
          ),
          const SizedBox(height: 16),
          Divider(color: AppTheme.borderColor),
          const SizedBox(height: 16),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator(color: AppTheme.primaryColor))
                : _items.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.notifications_off_outlined, color: AppTheme.textMuted, size: 48),
                            const SizedBox(height: 12),
                            Text('Belum ada aktivitas terbaru', style: GoogleFonts.inter(color: AppTheme.textSecondary)),
                          ],
                        ),
                      )
                    : ListView.separated(
                        itemCount: _items.length,
                        separatorBuilder: (_, __) => const SizedBox(height: 12),
                        itemBuilder: (_, i) {
                          final item = _items[i];
                          final type = item['type'] ?? 'info';
                          Color bg = AppTheme.primaryColor;
                          IconData ic = Icons.info_outline;
                          if (type == 'success') {
                            bg = AppTheme.accentGreen;
                            ic = Icons.check_circle_outline;
                          } else if (type == 'warning') {
                            bg = AppTheme.accentOrange;
                            ic = Icons.warning_amber_rounded;
                          } else if (type == 'danger') {
                            bg = AppTheme.accentRed;
                            ic = Icons.error_outline;
                          }
                          return GestureDetector(
                            onTap: () {
                              Navigator.pop(context);
                              if (type == 'success') {
                                widget.onSelectTab(1);
                              } else if (type == 'info') {
                                widget.onSelectTab(2);
                              } else {
                                widget.onSelectTab(3);
                              }
                            },
                            behavior: HitTestBehavior.opaque,
                            child: Container(
                              padding: const EdgeInsets.all(16),
                              decoration: BoxDecoration(
                                color: AppTheme.bgPrimary.withOpacity(0.5),
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: AppTheme.borderColor),
                              ),
                              child: Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Container(
                                    padding: const EdgeInsets.all(8),
                                    decoration: BoxDecoration(color: bg.withOpacity(0.15), borderRadius: BorderRadius.circular(8)),
                                    child: Icon(ic, color: bg, size: 20),
                                  ),
                                  const SizedBox(width: 12),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                          children: [
                                            Expanded(child: Text(item['title'] ?? '', style: GoogleFonts.inter(color: AppTheme.textPrimary, fontWeight: FontWeight.w600, fontSize: 14))),
                                            Text(item['time'] ?? '', style: GoogleFonts.inter(color: AppTheme.textMuted, fontSize: 11)),
                                          ],
                                        ),
                                        const SizedBox(height: 4),
                                        Text(item['message'] ?? '', style: GoogleFonts.inter(color: AppTheme.textSecondary, fontSize: 12)),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }
}

class _NavItem {
  final IconData icon;
  final String label;
  const _NavItem(this.icon, this.label);
}
