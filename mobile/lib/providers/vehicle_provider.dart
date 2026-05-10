import 'package:flutter/material.dart';
import '../models/vehicule.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';

class VehicleProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  List<Vehicule> _vehicles = [];
  bool _isLoading = false;

  List<Vehicule> get vehicles => _vehicles;
  bool get isLoading => _isLoading;

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

  Future<bool> bookVehicle(int vehicleId, String dateDebut, String dateFin, int? reservationId, String? notes) async {
    try {
      final response = await _apiService.post("${ApiConfig.vehicules}/louer", data: {
        'id_vehicule': vehicleId,
        'date_debut': dateDebut,
        'date_fin': dateFin,
        'id_reservation': reservationId,
        'notes': notes,
      });
      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }
}
