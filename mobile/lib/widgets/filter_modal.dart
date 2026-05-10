import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../utils/theme.dart';
import '../providers/apartment_provider.dart';
import 'package:intl/intl.dart';

class FilterModal extends StatefulWidget {
  final Function(Map<String, dynamic>) onApply;

  const FilterModal({super.key, required this.onApply});

  @override
  State<FilterModal> createState() => _FilterModalState();
}

class _FilterModalState extends State<FilterModal> {
  RangeValues _priceRange = const RangeValues(10000, 200000);
  int? _selectedCapacite;
  int? _selectedTypeId;
  List<int> _selectedEquipementIds = [];
  DateTimeRange? _selectedDateRange;

  void _selectDates() async {
    final DateTimeRange? picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 365)),
      initialDateRange: _selectedDateRange,
    );
    if (picked != null) {
      setState(() {
        _selectedDateRange = picked;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final provider = context.watch<ApartmentProvider>();
    final types = provider.availableFilters['types'] as List? ?? [];
    final equipements = provider.availableFilters['equipements'] as List? ?? [];

    return DraggableScrollableSheet(
      initialChildSize: 0.9,
      minChildSize: 0.5,
      maxChildSize: 0.95,
      expand: false,
      builder: (_, scrollController) {
        return Container(
          padding: const EdgeInsets.all(24),
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
          ),
          child: SingleChildScrollView(
            controller: scrollController,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildHeader(),
                const SizedBox(height: 24),
                _buildSectionTitle('Dates de séjour'),
                const SizedBox(height: 12),
                _buildDatePicker(),
                const SizedBox(height: 24),
                _buildSectionTitle('Type d\'appartement'),
                const SizedBox(height: 12),
                _buildTypeChips(types),
                const SizedBox(height: 24),
                _buildSectionTitle('Budget max (FCFA)'),
                _buildPriceSlider(),
                const SizedBox(height: 24),
                _buildSectionTitle('Capacité (Personnes)'),
                const SizedBox(height: 12),
                _buildCapacityChips(),
                const SizedBox(height: 24),
                _buildSectionTitle('Équipements'),
                const SizedBox(height: 12),
                _buildEquipementChips(equipements),
                const SizedBox(height: 40),
                _buildApplyButton(),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildHeader() {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        const Text(
          'Filtres Avancés',
          style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
        ),
        TextButton(
          onPressed: () {
            setState(() {
              _priceRange = const RangeValues(10000, 200000);
              _selectedCapacite = null;
              _selectedTypeId = null;
              _selectedEquipementIds = [];
              _selectedDateRange = null;
            });
          },
          child: const Text(
            'Réinitialiser',
            style: TextStyle(color: AppColors.primary),
          ),
        ),
      ],
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8.0),
      child: Text(
        title,
        style: const TextStyle(
          fontSize: 16,
          fontWeight: FontWeight.bold,
          color: AppColors.textDark,
        ),
      ),
    );
  }

  Widget _buildDatePicker() {
    return InkWell(
      onTap: _selectDates,
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: AppColors.background,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          children: [
            const Icon(Icons.calendar_month, color: AppColors.primary),
            const SizedBox(width: 12),
            Text(
              _selectedDateRange == null
                  ? 'Toutes les dates'
                  : '${DateFormat('dd/MM').format(_selectedDateRange!.start)} - ${DateFormat('dd/MM').format(_selectedDateRange!.end)}',
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTypeChips(List types) {
    return Wrap(
      spacing: 8,
      children: [
        ChoiceChip(
          label: const Text('Tous'),
          selected: _selectedTypeId == null,
          onSelected: (val) => setState(() => _selectedTypeId = null),
        ),
        ...types.map((type) {
          return ChoiceChip(
            label: Text(type['nom'] ?? ''),
            selected: _selectedTypeId == type['id'],
            onSelected: (val) => setState(() => _selectedTypeId = val ? type['id'] : null),
          );
        }),
      ],
    );
  }

  Widget _buildPriceSlider() {
    return Column(
      children: [
        RangeSlider(
          values: _priceRange,
          min: 10000,
          max: 200000,
          divisions: 38,
          activeColor: AppColors.primary,
          labels: RangeLabels(
            '${_priceRange.start.round()} F',
            '${_priceRange.end.round()} F',
          ),
          onChanged: (val) => setState(() => _priceRange = val),
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              '${_priceRange.start.round()} F',
              style: const TextStyle(fontSize: 12, color: AppColors.textLight),
            ),
            Text(
              '${_priceRange.end.round()} F',
              style: const TextStyle(fontSize: 12, color: AppColors.textLight),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildCapacityChips() {
    return Wrap(
      spacing: 8,
      children: List.generate(6, (index) {
        final cap = index + 1;
        return ChoiceChip(
          label: Text('$cap+'),
          selected: _selectedCapacite == cap,
          onSelected: (val) => setState(() => _selectedCapacite = val ? cap : null),
        );
      }),
    );
  }

  Widget _buildEquipementChips(List equipements) {
    return Wrap(
      spacing: 8,
      runSpacing: 4,
      children: equipements.map((eq) {
        final isSelected = _selectedEquipementIds.contains(eq['id']);
        return FilterChip(
          label: Text(
            eq['nom'] ?? '',
            style: TextStyle(
              fontSize: 12,
              color: isSelected ? Colors.white : AppColors.textDark,
            ),
          ),
          selected: isSelected,
          selectedColor: AppColors.primary,
          checkmarkColor: Colors.white,
          onSelected: (val) {
            setState(() {
              if (val) {
                _selectedEquipementIds.add(eq['id']);
              } else {
                _selectedEquipementIds.remove(eq['id']);
              }
            });
          },
        );
      }).toList(),
    );
  }

  Widget _buildApplyButton() {
    return SizedBox(
      width: double.infinity,
      height: 56,
      child: ElevatedButton(
        onPressed: () {
          final filters = <String, dynamic>{
            'prix_max': _priceRange.end,
            'prix_min': _priceRange.start,
          };
          if (_selectedTypeId != null) filters['type'] = _selectedTypeId;
          if (_selectedCapacite != null) filters['capacite'] = _selectedCapacite;
          if (_selectedEquipementIds.isNotEmpty) {
            filters['equipements'] = _selectedEquipementIds;
          }
          if (_selectedDateRange != null) {
            filters['date_arrivee'] =
                DateFormat('yyyy-MM-dd').format(_selectedDateRange!.start);
            filters['date_depart'] =
                DateFormat('yyyy-MM-dd').format(_selectedDateRange!.end);
          }
          widget.onApply(filters);
          Navigator.pop(context);
        },
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          foregroundColor: Colors.white,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
        ),
        child: const Text(
          'Appliquer les filtres',
          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
        ),
      ),
    );
  }
}
