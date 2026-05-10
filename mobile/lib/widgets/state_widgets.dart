import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';

class StateWidgets {
  // Widget d'erreur élégant
  static Widget error({
    required String message,
    required VoidCallback onRetry,
    String? title,
    bool compact = false,
  }) {
    return Padding(
      padding: EdgeInsets.all(compact ? 12.0 : 24.0),
      child: Center(
        child: SingleChildScrollView(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: EdgeInsets.all(compact ? 12 : 24),
                decoration: BoxDecoration(
                  color: Colors.red[50],
                  shape: BoxShape.circle,
                ),
                child: Icon(
                  Icons.wifi_off_rounded,
                  size: compact ? 40 : 80,
                  color: Colors.red[400],
                ),
              ),
              SizedBox(height: compact ? 12 : 24),
              Text(
                title ?? 'Oups ! Problème',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: compact ? 16 : 20,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textDark,
                ),
              ),
              SizedBox(height: compact ? 4 : 12),
              Text(
                _formatErrorMessage(message),
                textAlign: TextAlign.center,
                maxLines: compact ? 2 : null,
                overflow: compact ? TextOverflow.ellipsis : null,
                style: TextStyle(
                  fontSize: compact ? 11 : 14,
                  color: AppColors.textLight,
                ),
              ),
              SizedBox(height: compact ? 16 : 32),
              ElevatedButton.icon(
                onPressed: onRetry,
                icon: Icon(Icons.refresh_rounded, size: compact ? 18 : 24),
                label: Text(compact ? 'Réessayer' : 'Réessayer la connexion'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  padding: EdgeInsets.symmetric(
                    horizontal: compact ? 16 : 32,
                    vertical: compact ? 8 : 16,
                  ),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(compact ? 8 : 12),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  // Widget de chargement premium
  static Widget loading({String? message}) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const SpinKitDoubleBounce(
            color: AppColors.primary,
            size: 60.0,
          ),
          if (message != null) ...[
            const SizedBox(height: 16),
            Text(
              message,
              style: const TextStyle(
                color: AppColors.textLight,
                fontWeight: FontWeight.w500,
              ),
            ),
          ],
        ],
      ),
    );
  }

  // Widget de succès/vide
  static Widget empty({
    required String title,
    required String message,
    required IconData icon,
    bool compact = false,
  }) {
    return Padding(
      padding: EdgeInsets.all(compact ? 16.0 : 32.0),
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              icon,
              size: compact ? 50 : 80,
              color: AppColors.primary.withOpacity(0.2),
            ),
            SizedBox(height: compact ? 12 : 24),
            Text(
              title,
              style: TextStyle(
                fontSize: compact ? 16 : 18,
                fontWeight: FontWeight.bold,
                color: AppColors.textDark,
              ),
            ),
            SizedBox(height: compact ? 4 : 8),
            Text(
              message,
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: compact ? 12 : 14,
                color: AppColors.textLight,
              ),
            ),
          ],
        ),
      ),
    );
  }

  static String _formatErrorMessage(String rawMessage) {
    if (rawMessage.contains('Connection refused')) {
      return "Le serveur est injoignable. Vérifiez que votre backend Laravel est lancé et que l'adresse IP est correcte.";
    }
    if (rawMessage.contains('SocketException')) {
      return "Problème de connexion réseau. Veuillez vérifier votre accès internet.";
    }
    return rawMessage;
  }
}
