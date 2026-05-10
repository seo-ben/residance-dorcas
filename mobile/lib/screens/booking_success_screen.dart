import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_spinkit/flutter_spinkit.dart';
import '../providers/booking_provider.dart';
import '../utils/theme.dart';

class BookingSuccessScreen extends StatelessWidget {
  final int reservationId;
  final String reference;

  const BookingSuccessScreen({
    super.key,
    required this.reservationId,
    required this.reference,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(32.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                width: 120,
                height: 120,
                decoration: BoxDecoration(
                  color: Colors.green[50],
                  shape: BoxShape.circle,
                ),
                child: const Center(
                  child: SpinKitDoubleBounce(
                    color: Colors.green,
                    size: 100,
                  ),
                ),
              ),
              const SizedBox(height: 32),
              const Text(
                'Réservation Réussie !',
                style: TextStyle(
                  fontSize: 26,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textDark,
                ),
              ),
              const SizedBox(height: 16),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                decoration: BoxDecoration(
                  color: AppColors.background,
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  'Référence: #$reference',
                  style: const TextStyle(
                    fontWeight: FontWeight.bold,
                    color: AppColors.primary,
                  ),
                ),
              ),
              const SizedBox(height: 24),
              const Text(
                'Votre demande a été transmise à nos équipes. Pour garantir votre séjour, procédez au paiement de l\'acompte.',
                textAlign: TextAlign.center,
                style: TextStyle(
                  color: AppColors.textLight,
                  fontSize: 15,
                  height: 1.5,
                ),
              ),
              const SizedBox(height: 48),
              _buildPrimaryButton(context),
              const SizedBox(height: 16),
              _buildSecondaryButton(context),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildPrimaryButton(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      height: 56,
      child: ElevatedButton.icon(
        onPressed: () => context.read<BookingProvider>().launchPayment(reservationId),
        icon: const Icon(Icons.payment_rounded, color: Colors.white),
        label: const Text(
          'Payer l\'acompte',
          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
        ),
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: Colors.white,
          elevation: 2,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
        ),
      ),
    );
  }

  Widget _buildSecondaryButton(BuildContext context) {
    return TextButton(
      onPressed: () => Navigator.of(context).popUntil((route) => route.isFirst),
      child: const Text(
        'Retourner à l\'accueil',
        style: TextStyle(
          color: AppColors.textLight,
          fontWeight: FontWeight.w600,
          decoration: TextDecoration.underline,
        ),
      ),
    );
  }
}
