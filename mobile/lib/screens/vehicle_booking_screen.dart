import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/vehicule.dart';
import '../providers/vehicle_provider.dart';
import '../providers/auth_provider.dart';
import '../providers/booking_provider.dart';
import '../utils/theme.dart';
import 'login_screen.dart';
import 'package:intl/intl.dart';

class VehicleBookingScreen extends StatefulWidget {
  final Vehicule vehicle;

  const VehicleBookingScreen({super.key, required this.vehicle});

  @override
  State<VehicleBookingScreen> createState() => _VehicleBookingScreenState();
}

class _VehicleBookingScreenState extends State<VehicleBookingScreen> {
  DateTimeRange? _selectedDateRange;
  int? _selectedReservationId;
  final _notesController = TextEditingController();

  @override
  void initState() {
    super.initState();
    Future.microtask(() => context.read<BookingProvider>().fetchReservations());
  }

  void _selectDates() async {
    final DateTimeRange? picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 90)),
    );
    if (picked != null) setState(() => _selectedDateRange = picked);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Louer un véhicule')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildVehicleCard(),
            const SizedBox(height: 32),
            const Text('Période de location', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),
            ListTile(
              leading: const Icon(Icons.date_range, color: AppColors.primary),
              title: Text(_selectedDateRange == null 
                  ? 'Choisir les dates' 
                  : '${DateFormat('dd/MM').format(_selectedDateRange!.start)} - ${DateFormat('dd/MM').format(_selectedDateRange!.end)}'),
              onTap: _selectDates,
              tileColor: AppColors.background,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            const SizedBox(height: 24),
            const Text('Lier à une réservation (Optionnel)', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),
            _buildReservationDropdown(),
            const SizedBox(height: 24),
            const Text('Notes (Optionnel)', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            TextField(
              controller: _notesController,
              maxLines: 3,
              decoration: InputDecoration(
                hintText: 'Ex: Besoin d\'un siège bébé...',
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
            const SizedBox(height: 40),
            _buildPriceSummary(),
          ],
        ),
      ),
    );
  }

  Widget _buildVehicleCard() {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            const Icon(Icons.directions_car, size: 40, color: AppColors.primary),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('${widget.vehicle.marque} ${widget.vehicle.modele}', 
                      style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  Text('${widget.vehicle.prixJournalier.toStringAsFixed(0)} FCFA / jour', 
                      style: const TextStyle(color: AppColors.secondary)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildReservationDropdown() {
    return Consumer<BookingProvider>(
      builder: (context, provider, child) {
        if (provider.reservations.isEmpty) {
          return const Text('Aucune réservation active pour lier ce véhicule.', 
              style: TextStyle(fontSize: 12, color: AppColors.textLight));
        }
        return DropdownButtonFormField<int>(
          value: _selectedReservationId,
          decoration: InputDecoration(
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
            contentPadding: const EdgeInsets.symmetric(horizontal: 16),
          ),
          hint: const Text('Sélectionner une réservation'),
          items: provider.reservations.map((res) {
            return DropdownMenuItem(
              value: res.id,
              child: Text('Réservation #${res.reference}'),
            );
          }).toList(),
          onChanged: (val) => setState(() => _selectedReservationId = val),
        );
      },
    );
  }

  Widget _buildPriceSummary() {
    int days = _selectedDateRange?.duration.inDays ?? 0;
    if (days == 0 && _selectedDateRange != null) days = 1;
    double total = days * widget.vehicle.prixJournalier;

    return Column(
      children: [
        if (days > 0)
          Padding(
            padding: const EdgeInsets.only(bottom: 16.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('Total pour $days jour(s)'),
                Text('${total.toStringAsFixed(0)} FCFA', 
                    style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: AppColors.primary)),
              ],
            ),
        ),
        SizedBox(
          width: double.infinity,
          height: 56,
          child: ElevatedButton(
            onPressed: _handleBooking,
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.primary,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: const Text('Confirmer la location', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          ),
        ),
      ],
    );
  }

  void _handleBooking() async {
    final auth = context.read<AuthProvider>();
    if (!auth.isAuthenticated) {
      Navigator.push(context, MaterialPageRoute(builder: (_) => const LoginScreen()));
      return;
    }

    if (_selectedDateRange == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Veuillez choisir les dates de location')));
      return;
    }

    final success = await context.read<VehicleProvider>().bookVehicle(
      widget.vehicle.id,
      DateFormat('yyyy-MM-dd').format(_selectedDateRange!.start),
      DateFormat('yyyy-MM-dd').format(_selectedDateRange!.end),
      _selectedReservationId,
      _notesController.text,
    );

    if (mounted) {
      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Demande de location envoyée !'), backgroundColor: Colors.green));
        Navigator.pop(context);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Erreur lors de la location'), backgroundColor: Colors.red));
      }
    }
  }
}
