import 'package:flutter/material.dart';
import '../models/vehicule.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';

class VehicleProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  List<Vehicule> _vehicles = [];
  List<dynamic> _userRentals = [];
  bool _isLoading = false;

  List<Vehicule> get vehicles => _vehicles;
  List<dynamic> get userRentals => _userRentals;
  bool get isLoading => _isLoading;

  Future<void> fetchUserRentals() async {
    _isLoading = true;
    _userRentals = [];
    notifyListeners();

    final String fullUrl =
        "${ApiConfig.baseUrl}${ApiConfig.vehicules}/mes-locations";
    debugPrint("----------------------------------------------------------");
    debugPrint("INFO RECHERCHE LOCATIONS MOBILE :");
    debugPrint("1. L'application mobile appelle l'URL complète: $fullUrl");
    debugPrint(
      "2. Le serveur identifie l'utilisateur par son Token d'authentification.",
    );
    debugPrint(
      "3. La recherche côté serveur est optimisée pour trouver le client par son ID interne ET par son Email.",
    );
    debugPrint(
      "4. Cela permet de récupérer toutes les locations, même en cas de doublons de profil.",
    );
    debugPrint("----------------------------------------------------------");

    try {
      final response = await _apiService.get(
        "${ApiConfig.vehicules}/mes-locations",
      );
      if (response.statusCode == 200) {
        _userRentals = response.data['data'];

        debugPrint("RESULTAT RECHERCHE :");
        debugPrint("- Nombre de locations reçues: ${_userRentals.length}");
        if (response.data['debug_search_info'] != null) {
          debugPrint(
            "- Infos debug serveur: ${response.data['debug_search_info']}",
          );
        }
        debugPrint(
          "----------------------------------------------------------",
        );
      }
    } catch (e) {
      debugPrint("Error fetching user vehicle rentals: $e");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchVehicles() async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.get(ApiConfig.vehicules);
      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data'];
        _vehicles = data.map((json) => Vehicule.fromJson(json)).toList();
      }
    } catch (e) {
      debugPrint("Error fetching vehicles: $e");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> bookVehicle(
    int vehicleId,
    String dateDebut,
    String dateFin,
    int? reservationId,
    String? notes,
  ) async {
    try {
      final response = await _apiService.post(
        "${ApiConfig.vehicules}/louer",
        data: {
          'id_vehicule': vehicleId,
          'date_debut': dateDebut,
          'date_fin': dateFin,
          'id_reservation': reservationId,
          'notes': notes,
        },
      );
      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }
}
