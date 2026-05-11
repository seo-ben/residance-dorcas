import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/reservation_model.dart';
import '../providers/booking_provider.dart';
import '../utils/theme.dart';
import 'package:intl/intl.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'payment_declaration_screen.dart';

class ReservationDetailScreen extends StatelessWidget {
  final ReservationModel reservation;

  const ReservationDetailScreen({super.key, required this.reservation});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Réservation #${reservation.reference}'),
        actions: [
          if (reservation.statut == 'en_attente_paiement')
            TextButton(
              onPressed: () => context.read<BookingProvider>().launchPayment(reservation.id),
              child: const Text('PAYER', style: TextStyle(color: AppColors.primary, fontWeight: FontWeight.bold)),
            ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildStatusHeader(),
            const SizedBox(height: 24),
            _buildApartmentInfo(),
            const SizedBox(height: 24),
            _buildStayDetails(),
            const SizedBox(height: 24),
            _buildPriceBreakdown(),
            const SizedBox(height: 40),
            if (reservation.statut == 'en_attente_paiement')
              _buildPaymentButton(context),
            const SizedBox(height: 16),
            _buildActionButtons(context),
          ],
        ),
      ),
    );
  }

  Widget _buildStatusHeader() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: _getStatusColor(reservation.statut).withOpacity(0.1),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: _getStatusColor(reservation.statut)),
      ),
      child: Row(
        children: [
          Icon(Icons.info_outline, color: _getStatusColor(reservation.statut)),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              'Statut: ${_getStatusLabel(reservation.statut)}',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: _getStatusColor(reservation.statut),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildApartmentInfo() {
    final chambre = reservation.chambre;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text('Hébergement', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
        const SizedBox(height: 12),
        Card(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          child: Column(
            children: [
              if (chambre?.image != null)
                ClipRRect(
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(12)),
                  child: CachedNetworkImage(
                    imageUrl: chambre!.image!,
                    height: 150,
                    width: double.infinity,
                    fit: BoxFit.cover,
                  ),
                ),
              ListTile(
                title: Text(chambre?.type ?? 'Appartement', style: const TextStyle(fontWeight: FontWeight.bold)),
                subtitle: Text(chambre?.propriete ?? 'Résidance Dorcas'),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildStayDetails() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text('Détails du séjour', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
        const SizedBox(height: 12),
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10)],
          ),
          child: Column(
            children: [
              _detailRow(Icons.login, 'Arrivée', DateFormat('dd MMM yyyy').format(reservation.dateArrivee)),
              const Divider(height: 24),
              _detailRow(Icons.logout, 'Départ', DateFormat('dd MMM yyyy').format(reservation.dateDepart)),
              const Divider(height: 24),
              _detailRow(Icons.nights_stay, 'Durée', '${reservation.dateDepart.difference(reservation.dateArrivee).inDays} nuits'),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildPriceBreakdown() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text('Résumé financier', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
        const SizedBox(height: 12),
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: AppColors.primary,
            borderRadius: BorderRadius.circular(12),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Montant Total', style: TextStyle(color: Colors.white, fontSize: 16)),
              Text(
                '${reservation.montantTotal.toStringAsFixed(0)} FCFA',
                style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildPaymentButton(BuildContext context) {
    return Column(
      children: [
        SizedBox(
          width: double.infinity,
          height: 56,
          child: ElevatedButton(
            onPressed: () => context.read<BookingProvider>().launchPayment(reservation.id),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: const Text('Payer en ligne', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          width: double.infinity,
          height: 56,
          child: OutlinedButton(
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => PaymentDeclarationScreen(
                    type: 'reservation',
                    itemId: reservation.id,
                    montant: reservation.montantTotal,
                    reference: reservation.reference,
                  ),
                ),
              );
            },
            style: OutlinedButton.styleFrom(
              side: const BorderSide(color: AppColors.primary, width: 2),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: const Text(
              'Déclarer un transfert (T-Money / Flooz)',
              style: TextStyle(color: AppColors.primary, fontSize: 14, fontWeight: FontWeight.bold),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildActionButtons(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: OutlinedButton.icon(
            onPressed: () {},
            icon: const Icon(Icons.download),
            label: const Text('Reçu PDF'),
            style: OutlinedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 12),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
          ),
        ),
        const SizedBox(width: 16),
        Expanded(
          child: OutlinedButton.icon(
            onPressed: () {},
            icon: const Icon(Icons.help_outline),
            label: const Text('Aide'),
            style: OutlinedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 12),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
          ),
        ),
      ],
    );
  }

  Widget _detailRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, size: 20, color: AppColors.primary),
        const SizedBox(width: 12),
        Text(label, style: const TextStyle(color: AppColors.textLight)),
        const Spacer(),
        Text(value, style: const TextStyle(fontWeight: FontWeight.bold)),
      ],
    );
  }

  Color _getStatusColor(String statut) {
    switch (statut) {
      case 'confirmee': return Colors.green;
      case 'en_attente_paiement': return Colors.orange;
      case 'annulee': return Colors.red;
      default: return Colors.grey;
    }
  }

  String _getStatusLabel(String statut) {
    switch (statut) {
      case 'confirmee': return 'Confirmée';
      case 'en_attente_paiement': return 'Attente Paiement';
      case 'annulee': return 'Annulée';
      default: return statut;
    }
  }
}
