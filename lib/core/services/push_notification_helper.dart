import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../theme/app_theme.dart';

class PushNotificationHelper {
  static void show(
    BuildContext context, {
    required String title,
    required String message,
    bool isSuccess = true,
    VoidCallback? onAction,
    String? actionLabel,
  }) {
    showDialog(
      context: context,
      barrierDismissible: true,
      barrierColor: Colors.black.withOpacity(0.65),
      builder: (_) => TweenAnimationBuilder<double>(
        tween: Tween(begin: 0.7, end: 1.0),
        duration: const Duration(milliseconds: 350),
        curve: Curves.easeOutBack,
        builder: (context, scale, child) => Transform.scale(
          scale: scale,
          child: Dialog(
            backgroundColor: Colors.transparent,
            elevation: 0,
            child: Container(
              padding: const EdgeInsets.fromLTRB(24, 32, 24, 24),
              decoration: BoxDecoration(
                color: AppTheme.bgCard,
                borderRadius: BorderRadius.circular(28),
                border: Border.all(
                  color: isSuccess
                      ? AppTheme.accentGreen.withOpacity(0.4)
                      : AppTheme.accentRed.withOpacity(0.4),
                  width: 1.5,
                ),
                boxShadow: [
                  BoxShadow(
                    color: (isSuccess ? AppTheme.accentGreen : AppTheme.accentRed).withOpacity(0.2),
                    blurRadius: 40,
                    spreadRadius: 4,
                  ),
                ],
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Icon
                  Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: (isSuccess ? AppTheme.accentGreen : AppTheme.accentRed).withOpacity(0.15),
                      shape: BoxShape.circle,
                      border: Border.all(
                        color: (isSuccess ? AppTheme.accentGreen : AppTheme.accentRed).withOpacity(0.3),
                        width: 2,
                      ),
                    ),
                    child: Icon(
                      isSuccess ? Icons.check_circle_outline_rounded : Icons.warning_amber_rounded,
                      color: isSuccess ? AppTheme.accentGreen : AppTheme.accentRed,
                      size: 54,
                    ),
                  ),
                  const SizedBox(height: 24),
                  Text(
                    title,
                    textAlign: TextAlign.center,
                    style: GoogleFonts.inter(
                      color: Colors.white,
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 10),
                  Text(
                    message,
                    textAlign: TextAlign.center,
                    style: GoogleFonts.inter(
                      color: AppTheme.textSecondary,
                      fontSize: 13.5,
                      height: 1.4,
                    ),
                  ),
                  const SizedBox(height: 28),
                  Row(
                    children: [
                      Expanded(
                        child: SizedBox(
                          height: 46,
                          child: ElevatedButton(
                            onPressed: () => Navigator.of(context).pop(),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: AppTheme.bgPrimary,
                              foregroundColor: AppTheme.textPrimary,
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                              elevation: 0,
                            ),
                            child: Text(
                              'Tutup',
                              style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w600),
                            ),
                          ),
                        ),
                      ),
                      if (onAction != null) ...[
                        const SizedBox(width: 12),
                        Expanded(
                          child: SizedBox(
                            height: 46,
                            child: ElevatedButton(
                              onPressed: () {
                                Navigator.of(context).pop();
                                onAction();
                              },
                              style: ElevatedButton.styleFrom(
                                backgroundColor: isSuccess ? AppTheme.accentGreen : AppTheme.accentRed,
                                foregroundColor: Colors.white,
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                                elevation: 0,
                              ),
                              child: Text(
                                actionLabel ?? 'Lihat Detail',
                                style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w600),
                              ),
                            ),
                          ),
                        ),
                      ],
                    ],
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
