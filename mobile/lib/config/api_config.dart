class ApiConfig {
  static const String baseUrl = 'https://dorcas.kirefrais.com/api';
  static const String storageUrl = 'https://dorcas.kirefrais.com/storage';
  static const String clientPrefix = 'client';

  // Auth
  static const String login = '/$clientPrefix/login';
  static const String loginGoogle = '/$clientPrefix/login/google';
  static const String register = '/$clientPrefix/register';
  static const String me = '/$clientPrefix/me';
  static const String logout = '/$clientPrefix/logout';

  // Client Endpoints
  static const String appartements = '/$clientPrefix/appartements';
  static const String proprietes = '/$clientPrefix/proprietes';
  static const String services = '/$clientPrefix/services';
  static const String vehicules = '/$clientPrefix/vehicules';
  static const String favoris = '/$clientPrefix/favoris';
  static const String reservations = '/$clientPrefix/reservations';
  static const String visites = '/$clientPrefix/visites';
  static const String searchInstant = '/$clientPrefix/search/instant';
  static const String declarePayment = '/$clientPrefix/paiements/declarer';
}
