class Vehicule {
  final int id;
  final String marque;
  final String modele;
  final double prixJournalier;
  final String statut;
  final String? image;

  Vehicule({
    required this.id,
    required this.marque,
    required this.modele,
    required this.prixJournalier,
    required this.statut,
    this.image,
  });

  factory Vehicule.fromJson(Map<String, dynamic> json) {
    return Vehicule(
      id: json['id'],
      marque: json['marque'] ?? '',
      modele: json['modele'] ?? '',
      prixJournalier: double.tryParse(json['prix_journalier']?.toString() ?? '0') ?? 0.0,
      statut: json['statut'] ?? '',
      image: json['primary_image'] != null 
          ? json['primary_image']['url_image'] ?? json['primary_image']['path']
          : null,
    );
  }
}
