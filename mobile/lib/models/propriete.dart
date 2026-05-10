import '../config/api_config.dart';

class Propriete {
  final int id;
  final String nom;
  final String adresse;
  final String description;
  final String? image;

  Propriete({
    required this.id,
    required this.nom,
    required this.adresse,
    required this.description,
    this.image,
  });

  factory Propriete.fromJson(Map<String, dynamic> json) {
    String? imageUrl = json['medias'] != null && (json['medias'] as List).isNotEmpty 
          ? json['medias'][0]['chemin_fichier'] 
          : null;

    if (imageUrl != null && !imageUrl.startsWith('http')) {
      imageUrl = '${ApiConfig.storageUrl}/$imageUrl';
    }

    return Propriete(
      id: json['id'] ?? 0,
      nom: json['nom_propriete'] ?? '',
      adresse: json['adresse'] ?? '',
      description: json['description'] ?? '',
      image: imageUrl,
    );
  }
}
