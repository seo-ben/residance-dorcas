import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';
import '../utils/theme.dart';
import '../screens/apartment_details_screen.dart';
import '../screens/service_list_screen.dart';
import '../screens/vehicle_list_screen.dart';
import '../models/chambre.dart';

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
              contentPadding: EdgeInsets.zero,
              leading: ClipRRect(
                borderRadius: BorderRadius.circular(8),
                child: item['image'] != null
                    ? CachedNetworkImage(
                        imageUrl: item['image'],
                        width: 50,
                        height: 50,
                        fit: BoxFit.cover,
                        errorWidget: (_, __, ___) => _buildPlaceholder(item['type']),
                      )
                    : _buildPlaceholder(item['type']),
              ),
              title: Text(item['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text(item['subtitle']),
              trailing: const Icon(Icons.chevron_right, size: 20),
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

  void _handleItemTap(BuildContext context, dynamic item) {
    // Navigation selon le type
    switch (item['type']) {
      case 'appartement':
        // Transformer les données minimales de la recherche en modèle Chambre
        final chambre = Chambre(
          id: item['id'],
          numero: item['title'],
          prix: 0.0, // Sera mis à jour par le détail
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
        Navigator.push(
          context,
          MaterialPageRoute(builder: (_) => const ServiceListScreen(showAppBar: true)),
        );
        break;
      case 'vehicule':
        Navigator.push(
          context,
          MaterialPageRoute(builder: (_) => const VehicleListScreen(showAppBar: true)),
        );
        break;
      default:
        // Optionnel : Gérer les types inconnus ou retour par défaut
        break;
    }
  }
}
