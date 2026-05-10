class ServiceModel {
  final int id;
  final String nom;
  final double prix;
  final String? description;
  final String statut;

  ServiceModel({
    required this.id,
    required this.nom,
    required this.prix,
    this.description,
    required this.statut,
  });

  factory ServiceModel.fromJson(Map<String, dynamic> json) {
    return ServiceModel(
      id: json['id'],
      nom: json['nom'] ?? '',
      prix: double.tryParse(json['prix']?.toString() ?? '0') ?? 0.0,
      description: json['description'],
      statut: json['statut'] ?? 'actif',
    );
  }
}
