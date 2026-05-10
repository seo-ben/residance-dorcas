class Avis {
  final int id;
  final double note;
  final String commentaire;
  final String clientNom;
  final DateTime date;

  Avis({
    required this.id,
    required this.note,
    required this.commentaire,
    required this.clientNom,
    required this.date,
  });

  factory Avis.fromJson(Map<String, dynamic> json) {
    return Avis(
      id: json['id'] ?? 0,
      note: double.tryParse(json['note']?.toString() ?? '0') ?? 0.0,
      commentaire: json['commentaire'] ?? '',
      clientNom: json['client']?['user']?['name'] ?? 'Client anonyme',
      date: DateTime.tryParse(json['created_at']?.toString() ?? '') ?? DateTime.now(),
    );
  }
}
