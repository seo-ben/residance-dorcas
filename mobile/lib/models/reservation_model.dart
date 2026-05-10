import 'chambre.dart';

class ReservationModel {
  final int id;
  final String reference;
  final DateTime dateArrivee;
  final DateTime dateDepart;
  final String statut;
  final double montantTotal;
  final Chambre? chambre;

  ReservationModel({
    required this.id,
    required this.reference,
    required this.dateArrivee,
    required this.dateDepart,
    required this.statut,
    required this.montantTotal,
    this.chambre,
  });

  factory ReservationModel.fromJson(Map<String, dynamic> json) {
    return ReservationModel(
      id: json['id'],
      reference: json['reference'] ?? '',
      dateArrivee: DateTime.parse(json['date_arrivee']),
      dateDepart: DateTime.parse(json['date_depart']),
      statut: json['statut'] ?? '',
      montantTotal: double.tryParse(json['montant_total']?.toString() ?? '0') ?? 0.0,
      chambre: json['details'] != null && (json['details'] as List).isNotEmpty
          ? Chambre.fromJson(json['details'][0]['chambre'])
          : null,
    );
  }
}
