import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/reservation_model.dart';
import '../providers/booking_provider.dart';
import '../utils/theme.dart';
import 'package:intl/intl.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'payment_declaration_screen.dart';

class ReservationDetailScreen extends StatefulWidget {
  final ReservationModel reservation;

  const ReservationDetailScreen({super.key, required this.reservation});

  @override
  State<ReservationDetailScreen> createState() => _ReservationDetailScreenState();
}

class _ReservationDetailScreenState extends State<ReservationDetailScreen> {
  bool _isPaying = false;
  late ReservationModel _reservation;

  @override
  void initState() {
    super.initState();
    _reservation = widget.reservation;
  }

  Future<void> _handlePayment() async {
    setState(() => _isPaying = true);
    try {
      await context.read<BookingProvider>().launchPayment(_reservation.id);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Erreur lors du lancement du paiement: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => _isPaying = false);
    }
  }

  Future<void> _refreshData() async {
    // On pourrait rajouter une méthode fetchSingleReservation dans le provider
    await context.read<BookingProvider>().fetchReservations();
    if (mounted) {
      setState(() {
        _reservation = context.read<BookingProvider>().reservations.firstWhere(
          (r) => r.id == _reservation.id,
          orElse: () => _reservation,
        );
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Réservation #${_reservation.reference}'),
        actions: [
          if (_reservation.statut == 'en_attente_paiement')
            TextButton(
              onPressed: _isPaying ? null : _handlePayment,
              child: _isPaying 
                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2))
                : const Text('PAYER', style: TextStyle(color: AppColors.primary, fontWeight: FontWeight.bold)),
            ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _refreshData,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
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
              if (_reservation.statut == 'en_attente_paiement')
                _buildPaymentButton(context),
              const SizedBox(height: 16),
              _buildActionButtons(context),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStatusHeader() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: _getStatusColor(_reservation.statut).withValues(alpha: 0.1),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: _getStatusColor(_reservation.statut)),
      ),
      child: Row(
        children: [
          Icon(Icons.info_outline, color: _getStatusColor(_reservation.statut)),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              'Statut: ${_getStatusLabel(_reservation.statut)}',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: _getStatusColor(_reservation.statut),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildApartmentInfo() {
    final chambre = _reservation.chambre;
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
            boxShadow: [BoxShadow(color: Colors.black.withValues(alpha: 0.05), blurRadius: 10)],
          ),
          child: Column(
            children: [
              _detailRow(Icons.login, 'Arrivée', DateFormat('dd MMM yyyy').format(_reservation.dateArrivee)),
              const Divider(height: 24),
              _detailRow(Icons.logout, 'Départ', DateFormat('dd MMM yyyy').format(_reservation.dateDepart)),
              const Divider(height: 24),
              _detailRow(Icons.nights_stay, 'Durée', '${_reservation.dateDepart.difference(_reservation.dateArrivee).inDays} nuits'),
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
                '${_reservation.montantTotal.toStringAsFixed(0)} FCFA',
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
            onPressed: _isPaying ? null : _handlePayment,
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: _isPaying 
              ? const CircularProgressIndicator(color: Colors.white)
              : const Text('Payer en ligne', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          ),
        ),
        const SizedBox(height: 16),
        SizedBox(
          width: double.infinity,
          height: 56,
          child: OutlinedButton(
            onPressed: () async {
              await Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => PaymentDeclarationScreen(
                    type: 'reservation',
                    itemId: _reservation.id,
                    montant: _reservation.montantTotal,
                    reference: _reservation.reference,
                  ),
                ),
              );
              // Rafraichir les données au retour
              _refreshData();
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
      case 'en_attente_validation': return Colors.blue;
      case 'annulee': return Colors.red;
      default: return Colors.grey;
    }
  }

  String _getStatusLabel(String statut) {
    switch (statut) {
      case 'confirmee': return 'Confirmée';
      case 'en_attente_paiement': return 'Attente Paiement';
      case 'en_attente_validation': return 'Validation en cours';
      case 'annulee': return 'Annulée';
      default: return statut;
    }
  }
}
