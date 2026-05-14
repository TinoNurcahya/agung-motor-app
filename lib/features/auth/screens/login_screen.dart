import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/providers/auth_provider.dart';
import '../../../core/theme/app_theme.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen>
    with SingleTickerProviderStateMixin {
  final _emailCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  final _formKey = GlobalKey<FormState>();
  bool _obscure = true;
  bool _rememberMe = true;
  late AnimationController _animCtrl;
  late Animation<double> _fadeAnim;
  late Animation<Offset> _slideAnim;

  @override
  void initState() {
    super.initState();
    _animCtrl = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 800),
    );
    _fadeAnim = Tween<double>(begin: 0, end: 1).animate(
      CurvedAnimation(parent: _animCtrl, curve: Curves.easeOut),
    );
    _slideAnim = Tween<Offset>(
      begin: const Offset(0, 0.3),
      end: Offset.zero,
    ).animate(CurvedAnimation(parent: _animCtrl, curve: Curves.easeOutCubic));
    _animCtrl.forward();

    _loadRememberedEmail();
  }

  Future<void> _loadRememberedEmail() async {
    final prefs = await SharedPreferences.getInstance();
    final rem = prefs.getBool('remember_me') ?? true;
    final email = prefs.getString('remember_email') ?? '';
    if (mounted) {
      setState(() {
        _rememberMe = rem;
        if (rem && email.isNotEmpty) {
          _emailCtrl.text = email;
        }
      });
    }
  }

  @override
  void dispose() {
    _animCtrl.dispose();
    _emailCtrl.dispose();
    _passCtrl.dispose();
    super.dispose();
  }

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;
    final auth = context.read<AuthProvider>();
    final ok = await auth.login(_emailCtrl.text.trim(), _passCtrl.text, rememberMe: _rememberMe);
    if (!ok && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(auth.error ?? 'Email atau password salah'),
          backgroundColor: AppTheme.accentRed,
        ),
      );
    } else if (ok && mounted) {
      // 1. Tampilkan notifikasi SnackBar Sukses
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            'Login Berhasil! Selamat datang, ${auth.userName}.',
            style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w600),
          ),
          backgroundColor: AppTheme.accentGreen,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        ),
      );

      // 2. Tampilkan Popup Dialog Sukses yang Sangat Modern dan Futuristik
      showDialog(
        context: context,
        barrierDismissible: false,
        barrierColor: Colors.black.withOpacity(0.7),
        builder: (_) => TweenAnimationBuilder<double>(
          tween: Tween(begin: 0.8, end: 1.0),
          duration: const Duration(milliseconds: 300),
          curve: Curves.easeOutBack,
          builder: (context, scale, child) => Transform.scale(
            scale: scale,
            child: Dialog(
              backgroundColor: Colors.transparent,
              elevation: 0,
              child: BackdropFilter(
                filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 32),
                  decoration: BoxDecoration(
                    color: AppTheme.bgCard.withOpacity(0.9),
                    borderRadius: BorderRadius.circular(32),
                    border: Border.all(color: AppTheme.accentGreen.withOpacity(0.3), width: 1.5),
                    boxShadow: [
                      BoxShadow(
                        color: AppTheme.accentGreen.withOpacity(0.2),
                        blurRadius: 50,
                        spreadRadius: 5,
                      ),
                    ],
                  ),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      // Top pill indicator
                      Container(
                        width: 40,
                        height: 4,
                        margin: const EdgeInsets.only(bottom: 24),
                        decoration: BoxDecoration(
                          color: AppTheme.accentGreen.withOpacity(0.5),
                          borderRadius: BorderRadius.circular(10),
                        ),
                      ),
                      // Glowing Icon
                      Container(
                        padding: const EdgeInsets.all(22),
                        decoration: BoxDecoration(
                          color: AppTheme.accentGreen.withOpacity(0.15),
                          shape: BoxShape.circle,
                          border: Border.all(color: AppTheme.accentGreen.withOpacity(0.4), width: 2),
                          boxShadow: [
                            BoxShadow(
                              color: AppTheme.accentGreen.withOpacity(0.3),
                              blurRadius: 20,
                              spreadRadius: 2,
                            ),
                          ],
                        ),
                        child: const Icon(
                          Icons.verified_rounded,
                          color: AppTheme.accentGreen,
                          size: 58,
                        ),
                      ),
                      const SizedBox(height: 28),
                      Text(
                        'Otentikasi Berhasil',
                        style: GoogleFonts.inter(
                          color: Colors.white,
                          fontSize: 24,
                          fontWeight: FontWeight.w800,
                          letterSpacing: -0.5,
                        ),
                      ),
                      const SizedBox(height: 12),
                      Text(
                        'Selamat datang kembali, ${auth.userName}!\nSistem sedang menyiapkan ruang kerja Anda.',
                        textAlign: TextAlign.center,
                        style: GoogleFonts.inter(
                          color: AppTheme.textSecondary,
                          fontSize: 13.5,
                          height: 1.5,
                        ),
                      ),
                      const SizedBox(height: 32),
                      // Spinner with modern label
                      Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const SizedBox(
                            width: 20,
                            height: 20,
                            child: CircularProgressIndicator(
                              color: AppTheme.accentGreen,
                              strokeWidth: 2.5,
                            ),
                          ),
                          const SizedBox(width: 12),
                          Text(
                            'Memuat Enkripsi Sesi...',
                            style: GoogleFonts.inter(
                              color: AppTheme.accentGreen,
                              fontSize: 12,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      );

      // 3. Jeda 1.5 detik agar animasi terlihat, lalu masuk ke Dashboard
      Future.delayed(const Duration(milliseconds: 1500), () {
        if (mounted) {
          Navigator.of(context, rootNavigator: true).pop(); // Tutup dialog
          auth.completeLogin(); // Pindah ke Dashboard
        }
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.bgPrimary,
      body: Stack(
        children: [
          // Decorative Glow Blur Top-Right
          Positioned(
            top: -100,
            right: -100,
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                color: AppTheme.primaryColor.withOpacity(0.15),
                shape: BoxShape.circle,
              ),
              child: BackdropFilter(
                filter: ImageFilter.blur(sigmaX: 80, sigmaY: 80),
                child: Container(color: Colors.transparent),
              ),
            ),
          ),
          // Decorative Glow Blur Bottom-Left
          Positioned(
            bottom: -100,
            left: -100,
            child: Container(
              width: 300,
              height: 300,
              decoration: BoxDecoration(
                color: AppTheme.primaryColor.withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: BackdropFilter(
                filter: ImageFilter.blur(sigmaX: 80, sigmaY: 80),
                child: Container(color: Colors.transparent),
              ),
            ),
          ),

          SafeArea(
            child: Center(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                child: FadeTransition(
                  opacity: _fadeAnim,
                  child: SlideTransition(
                    position: _slideAnim,
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        // Logo / Brand matching Official Uploaded Design
                        Image.asset(
                          'assets/images/logo.png',
                          width: 250,
                          fit: BoxFit.contain,
                        ),
                        const SizedBox(height: 32),

                        // Main Glass Card
                        ClipRRect(
                          borderRadius: BorderRadius.circular(24),
                          child: BackdropFilter(
                            filter: ImageFilter.blur(sigmaX: 16, sigmaY: 16),
                            child: Container(
                              padding: const EdgeInsets.all(32),
                              decoration: BoxDecoration(
                                color: AppTheme.bgCard.withOpacity(0.7),
                                borderRadius: BorderRadius.circular(24),
                                border: Border.all(
                                  color: Colors.white.withOpacity(0.1),
                                  width: 1.5,
                                ),
                                boxShadow: [
                                  BoxShadow(
                                    color: Colors.black.withOpacity(0.2),
                                    blurRadius: 30,
                                    offset: const Offset(0, 10),
                                  ),
                                ],
                              ),
                              child: Form(
                                key: _formKey,
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    // Header Title inside Card
                                    Center(
                                      child: Column(
                                        children: [
                                          Text(
                                            'Selamat Datang',
                                            style: GoogleFonts.inter(
                                              color: AppTheme.textPrimary,
                                              fontSize: 22,
                                              fontWeight: FontWeight.w700,
                                            ),
                                          ),
                                          const SizedBox(height: 6),
                                          Text(
                                            'Masuk ke akun Anda untuk mengelola bengkel.',
                                            textAlign: TextAlign.center,
                                            style: GoogleFonts.inter(
                                              color: AppTheme.textSecondary,
                                              fontSize: 13,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                    const SizedBox(height: 28),

                                    // Email Address
                                    Text(
                                      'Email',
                                      style: GoogleFonts.inter(
                                        color: AppTheme.textSecondary,
                                        fontSize: 13,
                                        fontWeight: FontWeight.w600,
                                      ),
                                    ),
                                    const SizedBox(height: 8),
                                    TextFormField(
                                      controller: _emailCtrl,
                                      keyboardType: TextInputType.emailAddress,
                                      style: TextStyle(color: AppTheme.textPrimary),
                                      decoration: InputDecoration(
                                        hintText: 'Masukkan email Anda',
                                        hintStyle: TextStyle(color: AppTheme.textMuted),
                                        prefixIcon: const Icon(Icons.email_outlined, size: 20),
                                        filled: true,
                                        fillColor: AppTheme.bgPrimary.withOpacity(0.5),
                                        border: OutlineInputBorder(
                                          borderRadius: BorderRadius.circular(12),
                                          borderSide: BorderSide(color: AppTheme.borderColor),
                                        ),
                                        enabledBorder: OutlineInputBorder(
                                          borderRadius: BorderRadius.circular(12),
                                          borderSide: BorderSide(color: AppTheme.borderColor),
                                        ),
                                        focusedBorder: OutlineInputBorder(
                                          borderRadius: BorderRadius.circular(12),
                                          borderSide: const BorderSide(color: AppTheme.primaryColor),
                                        ),
                                        contentPadding: const EdgeInsets.symmetric(vertical: 16),
                                      ),
                                      validator: (v) =>
                                          v!.isEmpty ? 'Email wajib diisi' : null,
                                    ),
                                    const SizedBox(height: 20),

                                    // Password Header with Forgot Password Link
                                    Row(
                                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          'Password',
                                          style: GoogleFonts.inter(
                                            color: AppTheme.textSecondary,
                                            fontSize: 13,
                                            fontWeight: FontWeight.w600,
                                          ),
                                        ),
                                        // GestureDetector(
                                        //   onTap: () {},
                                        //   child: Text(
                                        //     'LUPA PASSWORD?',
                                        //     style: GoogleFonts.inter(
                                        //       color: AppTheme.primaryColor,
                                        //       fontSize: 11,
                                        //       fontWeight: FontWeight.w700,
                                        //     ),
                                        //   ),
                                        // ),
                                      ],
                                    ),
                                    const SizedBox(height: 8),
                                    TextFormField(
                                      controller: _passCtrl,
                                      obscureText: _obscure,
                                      style: TextStyle(color: AppTheme.textPrimary),
                                      decoration: InputDecoration(
                                        hintText: '••••••••',
                                        hintStyle: TextStyle(color: AppTheme.textMuted),
                                        prefixIcon: const Icon(Icons.lock_outline, size: 20),
                                        suffixIcon: IconButton(
                                          icon: Icon(
                                            _obscure
                                                ? Icons.visibility_outlined
                                                : Icons.visibility_off_outlined,
                                            color: AppTheme.textMuted,
                                            size: 20,
                                          ),
                                          onPressed: () =>
                                              setState(() => _obscure = !_obscure),
                                        ),
                                        filled: true,
                                        fillColor: AppTheme.bgPrimary.withOpacity(0.5),
                                        border: OutlineInputBorder(
                                          borderRadius: BorderRadius.circular(12),
                                          borderSide: BorderSide(color: AppTheme.borderColor),
                                        ),
                                        enabledBorder: OutlineInputBorder(
                                          borderRadius: BorderRadius.circular(12),
                                          borderSide: BorderSide(color: AppTheme.borderColor),
                                        ),
                                        focusedBorder: OutlineInputBorder(
                                          borderRadius: BorderRadius.circular(12),
                                          borderSide: const BorderSide(color: AppTheme.primaryColor),
                                        ),
                                        contentPadding: const EdgeInsets.symmetric(vertical: 16),
                                      ),
                                      validator: (v) =>
                                          v!.isEmpty ? 'Password wajib diisi' : null,
                                    ),
                                    const SizedBox(height: 16),

                                    // Remember Me Checkbox
                                    Row(
                                      children: [
                                        SizedBox(
                                          width: 24,
                                          height: 24,
                                          child: Checkbox(
                                            value: _rememberMe,
                                            onChanged: (val) => setState(() =>
                                                _rememberMe = val ?? true),
                                            activeColor: AppTheme.primaryColor,
                                            shape: RoundedRectangleBorder(
                                              borderRadius: BorderRadius.circular(4),
                                            ),
                                            side: BorderSide(color: AppTheme.textMuted),
                                          ),
                                        ),
                                        const SizedBox(width: 10),
                                        Text(
                                          'Ingat saya',
                                          style: GoogleFonts.inter(
                                            color: AppTheme.textSecondary,
                                            fontSize: 13,
                                            fontWeight: FontWeight.w500,
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 28),

                                    // Login Button matching Web
                                    Consumer<AuthProvider>(
                                      builder: (_, auth, __) => SizedBox(
                                        width: double.infinity,
                                        height: 52,
                                        child: ElevatedButton(
                                          onPressed: auth.isLoading ? null : _login,
                                          style: ElevatedButton.styleFrom(
                                            backgroundColor: AppTheme.primaryColor,
                                            shape: RoundedRectangleBorder(
                                              borderRadius: BorderRadius.circular(14),
                                            ),
                                            elevation: 4,
                                          ),
                                          child: auth.isLoading
                                              ? const SizedBox(
                                                  width: 20,
                                                  height: 20,
                                                  child: CircularProgressIndicator(
                                                    strokeWidth: 2,
                                                    color: Colors.white,
                                                  ),
                                                )
                                              : Row(
                                                  mainAxisAlignment: MainAxisAlignment.center,
                                                  children: [
                                                    const Icon(
                                                      Icons.login_rounded,
                                                      size: 18,
                                                      color: Colors.white,
                                                    ),
                                                    const SizedBox(width: 8),
                                                    Text(
                                                      'Masuk Sekarang',
                                                      style: GoogleFonts.inter(
                                                        fontSize: 15,
                                                        fontWeight: FontWeight.w600,
                                                        color: Colors.white,
                                                      ),
                                                    ),
                                                  ],
                                                ),
                                        ),
                                      ),
                                    ),
                                    const SizedBox(height: 24),

                                    // // Register Link
                                    // Row(
                                    //   mainAxisAlignment: MainAxisAlignment.center,
                                    //   children: [
                                    //     Text(
                                    //       'Belum punya akun? ',
                                    //       style: GoogleFonts.inter(
                                    //         color: AppTheme.textSecondary,
                                    //         fontSize: 13,
                                    //       ),
                                    //     ),
                                    //     GestureDetector(
                                    //       onTap: () {},
                                    //       child: Text(
                                    //         'Daftar Gratis',
                                    //         style: GoogleFonts.inter(
                                    //           color: AppTheme.primaryColor,
                                    //           fontSize: 13,
                                    //           fontWeight: FontWeight.w700,
                                    //         ),
                                    //       ),
                                    //     ),
                                    //   ],
                                    // ),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ),
                        const SizedBox(height: 32),

                        // Footer Copyright matching Web
                        Text(
                          '© 2026 Agung Motor App • Professional Service',
                          style: GoogleFonts.inter(
                            color: AppTheme.textMuted,
                            fontSize: 11,
                            fontWeight: FontWeight.w500,
                            letterSpacing: 1.0,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
