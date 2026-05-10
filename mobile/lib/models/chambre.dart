import '../config/api_config.dart';
import 'avis.dart';

class Chambre {
  final int id;
  final String numero;
  final double prix;
  final String statut;
  final String? type;
  final String? propriete;
  final String? image;
  final double note;
  final List<Avis> avis;

  Chambre({
    required this.id,
    required this.numero,
    required this.prix,
    required this.statut,
    this.type,
    this.propriete,
    this.image,
    this.note = 0.0,
    this.avis = const [],
  });

  factory Chambre.fromJson(Map<String, dynamic> json) {
    String? imageUrl = json['medias'] != null && (json['medias'] as List).isNotEmpty 
          ? json['medias'][0]['chemin_fichier'] 
          : null;

    if (imageUrl != null && !imageUrl.startsWith('http')) {
      imageUrl = '${ApiConfig.storageUrl}/$imageUrl';
    }

    var avisList = <Avis>[];
    if (json['avis'] != null && json['avis'] is List) {
      avisList = (json['avis'] as List).map((i) => Avis.fromJson(i)).toList();
    }

    return Chambre(
      id: json['id'] ?? 0,
      numero: json['numero_chambre'] ?? '',
      prix: double.tryParse(json['prix_base']?.toString() ?? '0') ?? 0.0,
      statut: json['statut'] ?? '',
      type: json['type_chambre']?['nom'],
      propriete: json['propriete']?['nom'],
      image: imageUrl,
      note: double.tryParse(json['note_moyenne']?.toString() ?? '0') ?? 0.0,
      avis: avisList,
    );
  }
}
