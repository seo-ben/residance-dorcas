import 'package:flutter/material.dart';
import '../models/reservation_model.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';
import 'package:url_launcher/url_launcher.dart';

class BookingProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  
  List<ReservationModel> _reservations = [];
  bool _isLoading = false;
  String? _error;

  List<ReservationModel> get reservations => _reservations;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> fetchReservations() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.get(ApiConfig.reservations);
      if (response.statusCode == 200) {
        final List<dynamic> data = response.data['data'];
        _reservations = data.map((json) => ReservationModel.fromJson(json)).toList();
      }
    } catch (e) {
      _error = "Erreur lors du chargement des réservations";
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<Map<String, dynamic>?> createReservation({
    required int chambreId,
    required DateTime dateArrivee,
    required DateTime dateDepart,
    String? notes,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.post(ApiConfig.reservations, data: {
        'chambre_id': chambreId,
        'date_arrivee': dateArrivee.toIso8601String().split('T')[0],
        'date_depart': dateDepart.toIso8601String().split('T')[0],
        'notes': notes,
        'save_draft': false,
      });

      if (response.statusCode == 200) {
        await fetchReservations();
        _isLoading = false;
        notifyListeners();
        return response.data['data'];
      }
    } catch (e) {
      _error = "Impossible de créer la réservation. Vérifiez la disponibilité.";
    }

    _isLoading = false;
    notifyListeners();
    return null;
  }

  Future<void> launchPayment(int reservationId) async {
    _isLoading = true;
    notifyListeners();
    
    try {
      final response = await _apiService.get("${ApiConfig.reservations}/$reservationId/paiement-link");
      if (response.statusCode == 200) {
        final url = Uri.parse(response.data['payment_url']);
        if (await canLaunchUrl(url)) {
          await launchUrl(url, mode: LaunchMode.externalApplication);
        }
      }
    } catch (e) {
      debugPrint("Payment launch error: $e");
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
