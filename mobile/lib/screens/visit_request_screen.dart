import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/chambre.dart';
import '../providers/visit_provider.dart';
import '../providers/auth_provider.dart';
import '../utils/theme.dart';
import 'login_screen.dart';
import 'package:intl/intl.dart';

class VisitRequestScreen extends StatefulWidget {
  final Chambre? apartment;

  const VisitRequestScreen({super.key, this.apartment});

  @override
  State<VisitRequestScreen> createState() => _VisitRequestScreenState();
}

class _VisitRequestScreenState extends State<VisitRequestScreen> {
  DateTime? _selectedDate;
  TimeOfDay? _selectedTime;
  final _notesController = TextEditingController();

  void _selectDate() async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now().add(const Duration(days: 1)),
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 30)),
    );
    if (picked != null) setState(() => _selectedDate = picked);
  }

  void _selectTime() async {
    final TimeOfDay? picked = await showTimePicker(
      context: context,
      initialTime: const TimeOfDay(hour: 10, minute: 0),
    );
    if (picked != null) setState(() => _selectedTime = picked);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Demander une visite')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (widget.apartment != null) ...[
              const Text('Appartement sélectionné', style: TextStyle(fontWeight: FontWeight.bold)),
              const SizedBox(height: 8),
              Card(
                child: ListTile(
                  leading: const Icon(Icons.apartment, color: AppColors.primary),
                  title: Text(widget.apartment!.type ?? 'Appartement'),
                  subtitle: Text(widget.apartment!.propriete ?? 'Résidance Dorcas'),
                ),
              ),
              const SizedBox(height: 24),
            ],
            const Text('Quand souhaitez-vous visiter ?', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            ListTile(
              leading: const Icon(Icons.calendar_today, color: AppColors.primary),
              title: Text(_selectedDate == null ? 'Choisir une date' : DateFormat('dd MMMM yyyy').format(_selectedDate!)),
              onTap: _selectDate,
              tileColor: AppColors.background,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            const SizedBox(height: 12),
            ListTile(
              leading: const Icon(Icons.access_time, color: AppColors.primary),
              title: Text(_selectedTime == null ? 'Choisir une heure' : _selectedTime!.format(context)),
              onTap: _selectTime,
              tileColor: AppColors.background,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            const SizedBox(height: 24),
            const Text('Notes ou questions (Optionnel)', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            TextField(
              controller: _notesController,
              maxLines: 4,
              decoration: InputDecoration(
                hintText: 'Précisez vos besoins particuliers...',
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
            const SizedBox(height: 40),
            Consumer<VisitProvider>(
              builder: (context, provider, child) {
                return SizedBox(
                  width: double.infinity,
                  height: 56,
                  child: ElevatedButton(
                    onPressed: provider.isLoading ? null : _handleSubmit,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    child: provider.isLoading
                        ? const CircularProgressIndicator(color: Colors.white)
                        : const Text('Envoyer la demande', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  ),
                );
              },
            ),
          ],
        ),
      ),
    );
  }

  void _handleSubmit() async {
    final auth = context.read<AuthProvider>();
    if (!auth.isAuthenticated) {
      Navigator.push(context, MaterialPageRoute(builder: (_) => const LoginScreen()));
      return;
    }

    if (widget.apartment == null || _selectedDate == null || _selectedTime == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Veuillez remplir tous les champs obligatoires')),
      );
      return;
    }

    final success = await context.read<VisitProvider>().requestVisit(
      chambreId: widget.apartment!.id,
      dateVisite: _selectedDate!,
      heureVisite: '${_selectedTime!.hour}:${_selectedTime!.minute}',
      notes: _notesController.text,
    );

    if (mounted) {
      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Votre demande de visite a été envoyée !'), backgroundColor: Colors.green),
        );
        Navigator.pop(context);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(context.read<VisitProvider>().error ?? 'Une erreur est survenue'), backgroundColor: Colors.red),
        );
      }
    }
  }
}
