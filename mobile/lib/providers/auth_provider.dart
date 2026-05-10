import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../config/api_config.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:io';
import 'package:google_sign_in/google_sign_in.dart';

class AuthProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  final GoogleSignIn _googleSignIn = GoogleSignIn(
    scopes: ['email', 'profile'],
  );
  
  bool _isAuthenticated = false;
  bool _isLoading = false;
  bool _isInitializing = true;
  bool _hasSeenOnboarding = false;
  Map<String, dynamic>? _user;
  String? _errorMessage;

  bool get isAuthenticated => _isAuthenticated;
  bool get isLoading => _isLoading;
  bool get isInitializing => _isInitializing;
  bool get hasSeenOnboarding => _hasSeenOnboarding;
  Map<String, dynamic>? get user => _user;
  String? get errorMessage => _errorMessage;

  AuthProvider() {
    checkInitialState();
  }

  Future<void> checkInitialState() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      
      // Check onboarding
      _hasSeenOnboarding = prefs.getBool('has_seen_onboarding') ?? false;

      // Check auth
      final token = prefs.getString('auth_token');
      if (token != null) {
        _isAuthenticated = true;
        // On ne bloque plus l'initialisation par fetchUser()
        // On lance le chargement en arrière-plan
        fetchUser(); 
      }
    } catch (e) {
      debugPrint("Initial state error: $e");
    } finally {
      _isInitializing = false;
      notifyListeners();
    }
  }

  Future<void> setOnboardingSeen() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('has_seen_onboarding', true);
    _hasSeenOnboarding = true;
    notifyListeners();
  }

  Future<void> checkAuthStatus() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    if (token != null) {
      _isAuthenticated = true;
      await fetchUser();
    }
    notifyListeners();
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final deviceName = Platform.isAndroid ? 'Android App' : 'iOS App';
      final response = await _apiService.post(ApiConfig.login, data: {
        'email': email,
        'password': password,
        'device_name': deviceName,
      });

      if (response.statusCode == 200 && response.data['success'] == true) {
        final token = response.data['token'];
        await _apiService.saveToken(token);
        _isAuthenticated = true;
        _user = response.data['user'];
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = response.data['message'] ?? 'Erreur d\'authentification';
      }
    } catch (e) {
      _errorMessage = "Impossible de se connecter. Vérifiez votre connexion.";
    }

    _isLoading = false;
    notifyListeners();
    return false;
  }

  Future<bool> loginWithGoogle() async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final GoogleSignInAccount? googleUser = await _googleSignIn.signIn();
      if (googleUser == null) {
        _isLoading = false;
        notifyListeners();
        return false;
      }

      final deviceName = Platform.isAndroid ? 'Android App (Google)' : 'iOS App (Google)';
      final response = await _apiService.post(ApiConfig.loginGoogle, data: {
        'email': googleUser.email,
        'google_id': googleUser.id,
        'name': googleUser.displayName ?? 'Utilisateur Google',
        'device_name': deviceName,
      });

      if (response.statusCode == 200 && response.data['success'] == true) {
        final token = response.data['token'];
        await _apiService.saveToken(token);
        _isAuthenticated = true;
        _user = response.data['user'];
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = response.data['message'] ?? 'Erreur de connexion Google';
      }
    } catch (e) {
      _errorMessage = "Erreur lors de la connexion avec Google: $e";
    }

    _isLoading = false;
    notifyListeners();
    return false;
  }

  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    required String indicatif,
    required String telephone,
  }) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final deviceName = Platform.isAndroid ? 'Android App' : 'iOS App';
      final response = await _apiService.post(ApiConfig.register, data: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'indicatif': indicatif,
        'telephone': telephone,
        'device_name': deviceName,
      });

      if (response.statusCode == 201 && response.data['success'] == true) {
        final token = response.data['token'];
        await _apiService.saveToken(token);
        _isAuthenticated = true;
        _user = response.data['user'];
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = response.data['message'] ?? 'Erreur lors de l\'inscription';
      }
    } catch (e) {
      _errorMessage = "Erreur lors de l'inscription. L'email est peut-être déjà utilisé.";
    }

    _isLoading = false;
    notifyListeners();
    return false;
  }

  Future<void> fetchUser() async {
    try {
      final response = await _apiService.get(ApiConfig.me);
      if (response.statusCode == 200 && response.data['success'] == true) {
        _user = response.data['user'];
        notifyListeners();
      }
    } catch (e) {
      // Token invalide ou expiré
      logout();
    }
  }

  Future<void> logout() async {
    try {
      if (_isAuthenticated) {
        await _apiService.post(ApiConfig.logout);
      }
    } catch (e) {
      debugPrint("Logout error: $e");
    } finally {
      await _apiService.removeToken();
      _isAuthenticated = false;
      _user = null;
      notifyListeners();
    }
  }
}
