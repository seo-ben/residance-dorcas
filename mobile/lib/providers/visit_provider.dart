import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';

class VisitProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  bool _isLoading = false;
  String? _error;

  bool get isLoading => _isLoading;
  String? get error => _error;
  List<dynamic> _visits = [];
  List<dynamic> get visits => _visits;

  Future<void> fetchVisits() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.get(ApiConfig.visites);
      if (response.statusCode == 200) {
        _visits = response.data['data'];
      }
    } catch (e) {
      _error = "Erreur lors du chargement des visites";
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> requestVisit({
    required int chambreId,
    required DateTime dateVisite,
    required String heureVisite,
    String? notes,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      // Note: Le backend Laravel a déjà un VisiteController
      // On utilise le prefix /client/visites si on l'a configuré
      final response = await _apiService.post(ApiConfig.visites, data: {
        'id_chambre': chambreId,
        'date_visite': dateVisite.toIso8601String().split('T')[0],
        'heure_visite': heureVisite,
        'notes': notes,
      });

      if (response.statusCode == 200 || response.statusCode == 201) {
        _isLoading = false;
        notifyListeners();
        return true;
      }
    } catch (e) {
      _error = "Une erreur est survenue lors de la demande de visite.";
    }

    _isLoading = false;
    notifyListeners();
    return false;
  }
}
