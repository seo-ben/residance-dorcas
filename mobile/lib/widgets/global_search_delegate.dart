import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';
import '../utils/theme.dart';
import '../screens/apartment_details_screen.dart';
import '../screens/vehicle_booking_screen.dart';
import '../screens/service_order_screen.dart';
import '../screens/apartment_list_screen.dart';
import '../screens/vehicle_list_screen.dart';
import '../models/chambre.dart';
import '../models/vehicule.dart';
import '../models/service_model.dart';

class GlobalSearchDelegate extends SearchDelegate {
  final ApiService _apiService = ApiService();

  @override
  String get searchFieldLabel => 'Rechercher un appartement, service...';

  @override
  List<Widget>? buildActions(BuildContext context) {
    return [
      if (query.isNotEmpty)
        IconButton(
          icon: const Icon(Icons.clear),
          onPressed: () => query = '',
        ),
    ];
  }

  @override
  Widget? buildLeading(BuildContext context) {
    return IconButton(
      icon: const Icon(Icons.arrow_back),
      onPressed: () => close(context, null),
    );
  }

  @override
  Widget buildResults(BuildContext context) {
    return _buildSearchResults();
  }

  @override
  Widget buildSuggestions(BuildContext context) {
    if (query.length < 2) {
      return const Center(
        child: Text('Entrez au moins 2 caractères pour rechercher'),
      );
    }
    return _buildSearchResults();
  }

  Widget _buildSearchResults() {
    return FutureBuilder(
      future: _apiService.get(ApiConfig.searchInstant, queryParameters: {'q': query}),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        }

        if (snapshot.hasError || !snapshot.hasData) {
          return const Center(child: Text('Erreur lors de la recherche'));
        }

        final List results = snapshot.data!.data['results'];

        if (results.isEmpty) {
          return const Center(child: Text('Aucun résultat trouvé'));
        }

        return ListView.separated(
          padding: const EdgeInsets.all(16),
          itemCount: results.length,
          separatorBuilder: (_, __) => const Divider(),
          itemBuilder: (context, index) {
            final item = results[index];
            return ListTile(
              leading: item['image'] != null
                  ? ClipRRect(
                      borderRadius: BorderRadius.circular(8),
                      child: Image.network(item['image'], width: 50, height: 50, fit: BoxFit.cover,
                        errorBuilder: (c, e, s) => const Icon(Icons.image_not_supported),
                      ),
                    )
                  : const Icon(Icons.search),
              title: Text(item['title'] ?? ''),
              subtitle: Text('${item['type']?.toString().toUpperCase()} • ${_getPrice(item).toStringAsFixed(0)} FCFA'),
              trailing: (item['type'] == 'service' || item['type'] == 'vehicule') 
                ? ElevatedButton(
                    onPressed: () => _handleItemTap(context, item),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      textStyle: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold),
                    ),
                    child: const Text('RÉSERVER'),
                  )
                : const Icon(Icons.arrow_forward_ios, size: 14),
              onTap: () => _handleItemTap(context, item),
            );
          },
        );
      },
    );
  }

  Widget _buildPlaceholder(String type) {
    if (type == 'appartement') {
      return Image.asset(
        'assets/images/exterieur de la résidence dorcas.png',
        width: 50,
        height: 50,
        fit: BoxFit.cover,
      );
    }
    
    IconData icon;
    switch (type) {
      case 'service': icon = Icons.room_service; break;
      case 'vehicule': icon = Icons.directions_car; break;
      default: icon = Icons.apartment;
    }
    return Container(
      width: 50,
      height: 50,
      color: AppColors.background,
      child: Icon(icon, color: AppColors.primary),
    );
  }

  double _getPrice(dynamic item) {
    if (item == null) return 0.0;
    
    // Check all possible field names
    final possibleFields = ['price', 'prix', 'prix_base', 'prix_journalier', 'prix_par_nuit','prix_unitaire','prix_original'];
    for (var field in possibleFields) {
      if (item[field] != null) {
        final val = item[field];
        if (val is num) return val.toDouble();
        return double.tryParse(val.toString()) ?? 0.0;
      }
    }
    
    // Fallback: extract from subtitle if it contains "FCFA" or "F"
    final subtitle = item['subtitle']?.toString() ?? '';
    if (subtitle.contains('FCFA') || subtitle.contains(' F')) {
      // Extract the last number in the string which is usually the price
      final matches = RegExp(r'(\d[\d\s]*)').allMatches(subtitle);
      if (matches.isNotEmpty) {
        final lastMatch = matches.last.group(0)!;
        final clean = lastMatch.replaceAll(RegExp(r'\s'), '');
        return double.tryParse(clean) ?? 0.0;
      }
    }
    
    return 0.0;
  }

  void _handleItemTap(BuildContext context, dynamic item) {
    // Navigation selon le type
    switch (item['type']) {
      case 'appartement':
        // Transformer les données minimales de la recherche en modèle Chambre
        final chambre = Chambre(
          id: item['id'],
          numero: item['title'],
          prix: _getPrice(item),
          statut: '',
          image: item['image'],
        );
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (_) => ApartmentDetailsScreen(apartment: chambre),
          ),
        );
        break;
      case 'service':
        final service = ServiceModel(
          id: item['id'],
          nom: item['title'] ?? 'Service',
          prix: _getPrice(item),
          statut: 'actif',
        );
        Navigator.push(
          context,
          MaterialPageRoute(builder: (_) => ServiceOrderScreen(service: service)),
        );
        break;
      case 'vehicule':
        final title = item['title']?.toString() ?? 'Véhicule';
        final parts = title.split(' ');
        final vehicule = Vehicule(
          id: item['id'],
          marque: parts.isNotEmpty ? parts[0] : 'Véhicule',
          modele: parts.length > 1 ? parts.skip(1).join(' ') : '',
          prixJournalier: _getPrice(item),
          statut: 'disponible',
          image: item['image'],
        );
        Navigator.push(
          context,
          MaterialPageRoute(builder: (_) => VehicleBookingScreen(vehicle: vehicule)),
        );
        break;
      default:
        // Optionnel : Gérer les types inconnus ou retour par défaut
        break;
    }
  }
}
