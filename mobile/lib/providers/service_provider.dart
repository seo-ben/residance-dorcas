import 'package:flutter/material.dart';
import '../models/service_model.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';

class ServiceProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  List<ServiceModel> _services = [];
  bool _isLoading = false;

  List<ServiceModel> get services => _services;
  bool get isLoading => _isLoading;

  Future<void> fetchServices() async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.get(ApiConfig.services);
      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data'];
        _services = data.map((json) => ServiceModel.fromJson(json)).toList();
      }
    } catch (e) {
      debugPrint("Error fetching services: $e");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> orderService(int serviceId, int quantity, String date, String time, String? notes) async {
    try {
      final response = await _apiService.post("${ApiConfig.services}/commander", data: {
        'id_service': serviceId,
        'quantite': quantity,
        'date_service': date,
        'heure_service': time,
        'notes': notes,
      });
      return response.statusCode == 200;
    } catch (e) {
      return false;
    }
  }
}
