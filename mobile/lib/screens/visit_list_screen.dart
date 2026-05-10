import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/visit_provider.dart';
import '../utils/theme.dart';
import 'package:intl/intl.dart';

class VisitListScreen extends StatefulWidget {
  const VisitListScreen({super.key});

  @override
  State<VisitListScreen> createState() => _VisitListScreenState();
}

class _VisitListScreenState extends State<VisitListScreen> {
  @override
  void initState() {
    super.initState();
    Future.microtask(() => context.read<VisitProvider>().fetchVisits());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mes Demandes de Visites')),
      body: Consumer<VisitProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading) return const Center(child: CircularProgressIndicator());
          
          if (provider.visits.isEmpty) {
            return const Center(child: Text('Aucune demande de visite trouvée.'));
          }

          return ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: provider.visits.length,
            itemBuilder: (context, index) {
              final visit = provider.visits[index];
              final date = DateTime.parse(visit['date_visite']);
              
              return Card(
                margin: const EdgeInsets.only(bottom: 16),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                child: ListTile(
                  leading: const CircleAvatar(
                    backgroundColor: AppColors.background,
                    child: Icon(Icons.calendar_month, color: AppColors.primary),
                  ),
                  title: Text(
                    visit['chambre']?['type_chambre']?['nom_type'] ?? 'Appartement',
                    style: const TextStyle(fontWeight: FontWeight.bold),
                  ),
                  subtitle: Text(
                    '${DateFormat('dd/MM/yyyy').format(date)} à ${visit['heure_visite']}',
                  ),
                  trailing: _buildStatusBadge(visit['statut'] ?? 'en_attente'),
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
      case 'en_attente':
        color = Colors.orange;
        label = 'En attente';
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
