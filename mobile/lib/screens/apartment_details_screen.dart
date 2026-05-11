import 'package:flutter/material.dart';
import '../models/chambre.dart';
import '../utils/theme.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:intl/intl.dart';

import 'package:provider/provider.dart';
import 'package:share_plus/share_plus.dart';
import '../providers/booking_provider.dart';
import '../providers/auth_provider.dart';
import '../providers/apartment_provider.dart';
import 'login_screen.dart';
import 'visit_request_screen.dart';
import 'booking_success_screen.dart';

class ApartmentDetailsScreen extends StatefulWidget {
  final Chambre apartment;

  const ApartmentDetailsScreen({super.key, required this.apartment});

  @override
  State<ApartmentDetailsScreen> createState() => _ApartmentDetailsScreenState();
}

class _ApartmentDetailsScreenState extends State<ApartmentDetailsScreen> {
  DateTimeRange? _selectedDateRange;

  void _selectDates() async {
    final DateTimeRange? picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 365)),
      initialDateRange: _selectedDateRange,
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: const ColorScheme.light(
              primary: AppColors.primary,
              onPrimary: Colors.white,
              onSurface: AppColors.textDark,
            ),
          ),
          child: child!,
        );
      },
    );

    if (picked != null) {
      setState(() {
        _selectedDateRange = picked;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: CustomScrollView(
        slivers: [
          _buildAppBar(context),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildHeader(),
                  const SizedBox(height: 24),
                  _buildSectionTitle('Période de séjour'),
                  const SizedBox(height: 12),
                  _buildDatePickerTile(),
                  const SizedBox(height: 24),
                  _buildSectionTitle('Description'),
                  const SizedBox(height: 8),
                  const Text(
                    'Profitez d\'un séjour exceptionnel dans cet appartement luxueux entièrement équipé. Idéalement situé, il offre tout le confort nécessaire pour vos déplacements professionnels ou vos vacances en famille.',
                    style: TextStyle(color: AppColors.textLight, height: 1.5),
                  ),
                  const SizedBox(height: 24),
                  _buildSectionTitle('Équipements'),
                  const SizedBox(height: 12),
                  _buildAmenities(),
                  const SizedBox(height: 32),
                  _buildSectionTitle('Avis clients'),
                  const SizedBox(height: 12),
                  _buildReviews(),
                  const SizedBox(height: 32),
                  _buildVisitButton(context),
                  const SizedBox(height: 100), // Espace pour le bouton flottant
                ],
              ),
            ),
          ),
        ],
      ),
      bottomSheet: _buildBottomAction(context),
    );
  }

  Widget _buildReviews() {
    final avis = widget.apartment.avis;
    if (avis.isEmpty) {
      return const Text(
        'Aucun avis pour le moment.',
        style: TextStyle(color: AppColors.textLight, fontSize: 14),
      );
    }

    return Column(
      children: avis.map((item) {
        return Container(
          margin: const EdgeInsets.only(bottom: 16),
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: AppColors.background,
            borderRadius: BorderRadius.circular(16),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    item.clientNom,
                    style: const TextStyle(fontWeight: FontWeight.bold),
                  ),
                  Row(
                    children: List.generate(5, (index) {
                      return Icon(
                        Icons.star,
                        size: 14,
                        color: index < item.note
                            ? Colors.amber
                            : Colors.grey[300],
                      );
                    }),
                  ),
                ],
              ),
              const SizedBox(height: 8),
              Text(
                item.commentaire,
                style: const TextStyle(fontSize: 14, color: AppColors.textDark),
              ),
              const SizedBox(height: 4),
              Text(
                DateFormat('dd MMM yyyy').format(item.date),
                style: const TextStyle(
                  fontSize: 12,
                  color: AppColors.textLight,
                ),
              ),
            ],
          ),
        );
      }).toList(),
    );
  }

  Widget _buildVisitButton(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      child: OutlinedButton.icon(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => VisitRequestScreen(apartment: widget.apartment),
            ),
          );
        },
        icon: const Icon(Icons.calendar_today, color: AppColors.primary),
        label: const Text(
          'Demander une visite',
          style: TextStyle(
            color: AppColors.primary,
            fontWeight: FontWeight.bold,
          ),
        ),
        style: OutlinedButton.styleFrom(
          padding: const EdgeInsets.symmetric(vertical: 16),
          side: const BorderSide(color: AppColors.primary),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
      ),
    );
  }

  Widget _buildDatePickerTile() {
    return InkWell(
      onTap: _selectDates,
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: AppColors.background,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: Colors.grey[200]!),
        ),
        child: Row(
          children: [
            const Icon(Icons.calendar_month, color: AppColors.primary),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                _selectedDateRange == null
                    ? 'Choisir vos dates'
                    : '${DateFormat('dd/MM').format(_selectedDateRange!.start)} - ${DateFormat('dd/MM').format(_selectedDateRange!.end)}',
                style: TextStyle(
                  color: _selectedDateRange == null
                      ? AppColors.textLight
                      : AppColors.textDark,
                  fontWeight: _selectedDateRange == null
                      ? FontWeight.normal
                      : FontWeight.bold,
                ),
              ),
            ),
            const Icon(Icons.edit, size: 16, color: AppColors.textLight),
          ],
        ),
      ),
    );
  }

  Widget _buildAppBar(BuildContext context) {
    return SliverAppBar(
      expandedHeight: 300,
      pinned: true,
      flexibleSpace: FlexibleSpaceBar(
        background: widget.apartment.image != null
            ? CachedNetworkImage(
                imageUrl: widget.apartment.image!,
                fit: BoxFit.cover,
              )
            : Container(
                color: Colors.grey[300],
                child: const Icon(Icons.apartment, size: 100),
              ),
      ),
      leading: CircleAvatar(
        backgroundColor: Colors.white,
        child: IconButton(
          icon: const Icon(Icons.arrow_back, color: AppColors.textDark),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      actions: [
        CircleAvatar(
          backgroundColor: Colors.white,
          child: Consumer<ApartmentProvider>(
            builder: (context, provider, child) {
              return IconButton(
                icon: const Icon(Icons.favorite, color: Colors.red),
                onPressed: () => provider.toggleFavorite(widget.apartment.id),
              );
            },
          ),
        ),
        const SizedBox(width: 10),
        CircleAvatar(
          backgroundColor: Colors.white,
          child: IconButton(
            icon: const Icon(Icons.share, color: AppColors.textDark),
            onPressed: () {
              Share.share(
                'Regardez cet appartement : ${widget.apartment.type} ${widget.apartment.numero} à ${widget.apartment.propriete ?? "Résidance Dorcas"}. Réservez maintenant sur Dorcas App!',
                subject: 'Partage d\'appartement',
              );
            },
          ),
        ),
        const SizedBox(width: 16),
      ],
    );
  }

  Widget _buildHeader() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Expanded(
              child: Text(
                '${widget.apartment.type ?? 'Appartement'} ${widget.apartment.numero}',
                style: const TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                ),
                overflow: TextOverflow.ellipsis,
              ),
            ),
            const SizedBox(width: 8),
            Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                const Icon(Icons.star, color: Colors.amber, size: 20),
                const SizedBox(width: 4),
                Text(
                  widget.apartment.note.toStringAsFixed(1),
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ],
        ),
        const SizedBox(height: 8),
        Row(
          children: [
            const Icon(Icons.location_on, color: AppColors.primary, size: 16),
            const SizedBox(width: 4),
            Expanded(
              child: Text(
                widget.apartment.propriete ?? 'Résidance Dorcas, Togo',
                style: const TextStyle(color: AppColors.textLight),
                overflow: TextOverflow.ellipsis,
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildSectionTitle(String title) {
    return Text(
      title,
      style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
    );
  }

  Widget _buildAmenities() {
    final amenities = [
      {'icon': Icons.wifi, 'label': 'Wi-Fi'},
      {'icon': Icons.ac_unit, 'label': 'Climatisation'},
      {'icon': Icons.tv, 'label': 'Smart TV'},
      {'icon': Icons.kitchen, 'label': 'Cuisine'},
      {'icon': Icons.pool, 'label': 'Piscine'},
    ];

    return Wrap(
      spacing: 16,
      runSpacing: 16,
      children: amenities.map((item) {
        return Container(
          width: 80,
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            border: Border.all(color: Colors.grey[200]!),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Column(
            children: [
              Icon(item['icon'] as IconData, color: AppColors.primary),
              const SizedBox(height: 4),
              Text(
                item['label'] as String,
                style: const TextStyle(fontSize: 10),
                textAlign: TextAlign.center,
              ),
            ],
          ),
        );
      }).toList(),
    );
  }

  Widget _buildBottomAction(BuildContext context) {
    double totalPrix = widget.apartment.prix;
    if (_selectedDateRange != null) {
      int days = _selectedDateRange!.duration.inDays;
      if (days == 0) days = 1;
      totalPrix = widget.apartment.prix * days;
    }

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, -5),
          ),
        ],
      ),
      child: Row(
        children: [
          Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                _selectedDateRange == null
                    ? 'Prix / nuit'
                    : '${_selectedDateRange!.duration.inDays} nuits',
                style: const TextStyle(color: AppColors.textLight),
              ),
              Text(
                '${totalPrix.toStringAsFixed(0)} FCFA',
                style: const TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary,
                ),
              ),
            ],
          ),
          const SizedBox(width: 24),
          Expanded(
            child: Consumer<BookingProvider>(
              builder: (context, bookingProvider, child) {
                return ElevatedButton(
                  onPressed: _selectedDateRange == null
                      ? _selectDates
                      : (bookingProvider.isLoading ? null : _handleBooking),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12),
                    ),
                  ),
                  child: bookingProvider.isLoading
                      ? const SizedBox(
                          height: 20,
                          width: 20,
                          child: CircularProgressIndicator(
                            color: Colors.white,
                            strokeWidth: 2,
                          ),
                        )
                      : Text(
                          _selectedDateRange == null
                              ? 'Choisir dates'
                              : 'Réserver',
                        ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  void _handleBooking() async {
    final auth = context.read<AuthProvider>();
    if (!auth.isAuthenticated) {
      Navigator.push(
        context,
        MaterialPageRoute(builder: (_) => const LoginScreen()),
      );
      return;
    }

    final reservationData = await context
        .read<BookingProvider>()
        .createReservation(
          chambreId: widget.apartment.id,
          dateArrivee: _selectedDateRange!.start,
          dateDepart: _selectedDateRange!.end,
        );

    if (mounted) {
      if (reservationData != null) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (_) => BookingSuccessScreen(
              reservationId: reservationData['id'],
              reference: reservationData['reference'],
            ),
          ),
        );
      } else {
        final error = context.read<BookingProvider>().error;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(error ?? 'Une erreur est survenue'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }
}
