import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppTheme {
  static bool isDarkMode = true;

  // Brand Colors — Red & Black Dominant
  static const Color primaryColor = Color(0xFFDC2626);    // Dominant Crimson Red
  static const Color primaryLight = Color(0xFFEF4444);
  static const Color primaryDark = Color(0xFF991B1B);
  static const Color accentGreen = Color(0xFF10B981);
  static const Color accentRed = Color(0xFFEF4444);
  static const Color accentOrange = Color(0xFFF59E0B);
  static const Color accentBlue = Color(0xFF64748B);      // Neutralized blue to slate

  // Dynamic Backgrounds
  static Color get bgPrimary => isDarkMode ? const Color(0xFF09090B) : const Color(0xFFF8FAFC);
  static Color get bgSecondary => isDarkMode ? const Color(0xFF18181B) : const Color(0xFFFFFFFF);
  static Color get bgCard => isDarkMode ? const Color(0xFF18181B) : const Color(0xFFFFFFFF);
  static Color get bgCardHover => isDarkMode ? const Color(0xFF27272A) : const Color(0xFFF1F5F9);
  static Color get borderColor => isDarkMode ? const Color(0xFF27272A) : const Color(0xFFE2E8F0);

  // Dynamic Text Colors
  static Color get textPrimary => isDarkMode ? const Color(0xFFF1F5F9) : const Color(0xFF0F172A);
  static Color get textSecondary => isDarkMode ? const Color(0xFF94A3B8) : const Color(0xFF64748B);
  static Color get textMuted => isDarkMode ? const Color(0xFF64748B) : const Color(0xFF94A3B8);

  static ThemeData get darkTheme {
    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.dark,
      scaffoldBackgroundColor: const Color(0xFF09090B),
      colorScheme: const ColorScheme.dark(
        primary: primaryColor,
        secondary: accentGreen,
        surface: Color(0xFF18181B),
        error: accentRed,
      ),
      textTheme: GoogleFonts.interTextTheme(ThemeData.dark().textTheme).copyWith(
        displayLarge: GoogleFonts.inter(color: const Color(0xFFF1F5F9), fontSize: 32, fontWeight: FontWeight.w700),
        headlineMedium: GoogleFonts.inter(color: const Color(0xFFF1F5F9), fontSize: 24, fontWeight: FontWeight.w600),
        titleLarge: GoogleFonts.inter(color: const Color(0xFFF1F5F9), fontSize: 18, fontWeight: FontWeight.w600),
        titleMedium: GoogleFonts.inter(color: const Color(0xFFF1F5F9), fontSize: 16, fontWeight: FontWeight.w500),
        bodyLarge: GoogleFonts.inter(color: const Color(0xFFF1F5F9), fontSize: 14),
        bodyMedium: GoogleFonts.inter(color: const Color(0xFF94A3B8), fontSize: 13),
        bodySmall: GoogleFonts.inter(color: const Color(0xFF64748B), fontSize: 12),
      ),
      cardTheme: CardThemeData(
        color: const Color(0xFF18181B), elevation: 0,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16), side: const BorderSide(color: Color(0xFF27272A), width: 1)),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true, fillColor: const Color(0xFF18181B),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFF27272A))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFF27272A))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: primaryColor, width: 2)),
        labelStyle: const TextStyle(color: Color(0xFF94A3B8)), hintStyle: const TextStyle(color: Color(0xFF64748B)), prefixIconColor: const Color(0xFF64748B),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: primaryColor, foregroundColor: Colors.white,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          textStyle: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w600),
        ),
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: Color(0xFF09090B), elevation: 0, surfaceTintColor: Colors.transparent,
        titleTextStyle: TextStyle(color: Color(0xFFF1F5F9), fontSize: 18, fontWeight: FontWeight.w600),
        iconTheme: IconThemeData(color: Color(0xFF94A3B8)),
      ),
      dividerTheme: const DividerThemeData(color: Color(0xFF27272A), thickness: 1),
    );
  }

  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.light,
      scaffoldBackgroundColor: const Color(0xFFF8FAFC),
      colorScheme: const ColorScheme.light(
        primary: primaryColor,
        secondary: accentGreen,
        surface: Color(0xFFFFFFFF),
        error: accentRed,
      ),
      textTheme: GoogleFonts.interTextTheme(ThemeData.light().textTheme).copyWith(
        displayLarge: GoogleFonts.inter(color: const Color(0xFF0F172A), fontSize: 32, fontWeight: FontWeight.w700),
        headlineMedium: GoogleFonts.inter(color: const Color(0xFF0F172A), fontSize: 24, fontWeight: FontWeight.w600),
        titleLarge: GoogleFonts.inter(color: const Color(0xFF0F172A), fontSize: 18, fontWeight: FontWeight.w600),
        titleMedium: GoogleFonts.inter(color: const Color(0xFF0F172A), fontSize: 16, fontWeight: FontWeight.w500),
        bodyLarge: GoogleFonts.inter(color: const Color(0xFF0F172A), fontSize: 14),
        bodyMedium: GoogleFonts.inter(color: const Color(0xFF64748B), fontSize: 13),
        bodySmall: GoogleFonts.inter(color: const Color(0xFF94A3B8), fontSize: 12),
      ),
      cardTheme: CardThemeData(
        color: const Color(0xFFFFFFFF), elevation: 0,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16), side: const BorderSide(color: Color(0xFFE2E8F0), width: 1)),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true, fillColor: const Color(0xFFFFFFFF),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: primaryColor, width: 2)),
        labelStyle: const TextStyle(color: Color(0xFF64748B)), hintStyle: const TextStyle(color: Color(0xFF94A3B8)), prefixIconColor: const Color(0xFF94A3B8),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: primaryColor, foregroundColor: Colors.white,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          textStyle: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w600),
        ),
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: Color(0xFFFFFFFF), elevation: 0, surfaceTintColor: Colors.transparent,
        titleTextStyle: TextStyle(color: Color(0xFF0F172A), fontSize: 18, fontWeight: FontWeight.w600),
        iconTheme: IconThemeData(color: Color(0xFF64748B)),
      ),
      dividerTheme: const DividerThemeData(color: Color(0xFFE2E8F0), thickness: 1),
    );
  }
}
