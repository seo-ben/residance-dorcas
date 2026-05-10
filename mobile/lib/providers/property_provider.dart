import 'package:flutter/material.dart';
import '../models/propriete.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';

class PropertyProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  List<Propriete> _properties = [];
  bool _isLoading = false;
  String? _error;

  List<Propriete> get properties => _properties;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchProperties() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.get(ApiConfig.proprietes);
      if (response.statusCode == 200 && response.data['success'] == true) {
        final dynamic rawData = response.data['data'];
        if (rawData is List) {
          _properties = rawData.map((json) => Propriete.fromJson(json)).toList();
        }
      } else {
        _error = "Erreur lors du chargement des propriétés";
      }
    } catch (e) {
      _error = "Une erreur est survenue: $e";
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
