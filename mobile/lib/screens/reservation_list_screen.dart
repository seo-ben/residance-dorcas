import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/booking_provider.dart';
import '../utils/theme.dart';
import 'package:intl/intl.dart';
import 'reservation_detail_screen.dart';

class ReservationListScreen extends StatefulWidget {
  const ReservationListScreen({super.key});

  @override
  State<ReservationListScreen> createState() => _ReservationListScreenState();
}

class _ReservationListScreenState extends State<ReservationListScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => context.read<BookingProvider>().fetchReservations());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mes Réservations')),
      body: Consumer<BookingProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading) return const Center(child: CircularProgressIndicator());
          
          if (provider.reservations.isEmpty) {
            return const Center(child: Text('Aucune réservation trouvée.'));
          }

          return ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: provider.reservations.length,
            itemBuilder: (context, index) {
              final res = provider.reservations[index];
              return Card(
                margin: const EdgeInsets.only(bottom: 16),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            'Réf: ${res.reference}',
                            style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary),
                          ),
                          _buildStatusBadge(res.statut),
                        ],
                      ),
                      const Divider(height: 24),
                      Row(
                        children: [
                          const Icon(Icons.calendar_today, size: 16, color: AppColors.textLight),
                          const SizedBox(width: 8),
                          Text(
                            '${DateFormat('dd/MM/yyyy').format(res.dateArrivee)} - ${DateFormat('dd/MM/yyyy').format(res.dateDepart)}',
                            style: const TextStyle(fontSize: 14),
                          ),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.hotel, size: 16, color: AppColors.textLight),
                          const SizedBox(width: 8),
                          Text(
                            res.chambre?.type ?? 'Appartement',
                            style: const TextStyle(fontSize: 14),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            '${res.montantTotal.toStringAsFixed(0)} FCFA',
                            style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.secondary),
                          ),
                          TextButton(
                            onPressed: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (_) => ReservationDetailScreen(reservation: res),
                                ),
                              );
                            },
                            child: const Text('Voir détails'),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }

  Widget _buildStatusBadge(String statut) {
    Color color;
    String label;

    switch (statut) {
      case 'confirmee':
        color = Colors.green;
        label = 'Confirmée';
        break;
      case 'en_attente_paiement':
        color = Colors.orange;
        label = 'Attente Paiement';
        break;
      case 'en_attente_validation':
        color = Colors.blue;
        label = 'Validation en cours';
        break;
      case 'annulee':
        color = Colors.red;
        label = 'Annulée';
        break;
      default:
        color = Colors.grey;
        label = statut;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: color),
      ),
      child: Text(
        label,
        style: TextStyle(color: color, fontSize: 10, fontWeight: FontWeight.bold),
      ),
    );
  }
}
