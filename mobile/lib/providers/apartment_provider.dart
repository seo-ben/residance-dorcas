import 'package:flutter/material.dart';
import '../models/chambre.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';

class ApartmentProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  List<Chambre> _apartments = [];
  Map<String, dynamic> _availableFilters = {};
  bool _isLoading = false;
  String? _error;

  List<Chambre> get apartments => _apartments;
  Map<String, dynamic> get availableFilters => _availableFilters;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchApartments({Map<String, dynamic>? filters}) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.get(ApiConfig.appartements, queryParameters: filters);
      if (response.statusCode == 200) {
        final dynamic rawData = response.data['data']['data'];
        if (rawData is List) {
          _apartments = rawData.map((json) => Chambre.fromJson(json)).toList();
        }
        _availableFilters = response.data['filters'] ?? {};
      } else {
        _error = "Erreur lors du chargement des appartements";
      }
    } catch (e) {
      _error = "Une erreur est survenue: $e";
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> toggleFavorite(int apartmentId) async {
    try {
      final response = await _apiService.post("${ApiConfig.favoris}/$apartmentId/toggle");
      if (response.statusCode == 200) {
        // Optionnel: rafraîchir la liste ou mettre à jour l'état local
        notifyListeners();
      }
    } catch (e) {
      debugPrint("Error toggling favorite: $e");
    }
  }

  Future<List<Chambre>> fetchFavorites() async {
    try {
      final response = await _apiService.get(ApiConfig.favoris);
      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data']['data'];
        return data.map((json) => Chambre.fromJson(json)).toList();
      }
    } catch (e) {
      debugPrint("Error fetching favorites: $e");
    }
    return [];
  }

  Future<Chambre?> getApartmentDetails(int id) async {
    try {
      final response = await _apiService.get("${ApiConfig.appartements}/$id");
      if (response.statusCode == 200) {
        return Chambre.fromJson(response.data['data']);
      }
    } catch (e) {
      debugPrint("Error fetching details: $e");
    }
    return null;
  }
}
