import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/chambre.dart';
import '../providers/apartment_provider.dart';
import '../utils/theme.dart';
import '../widgets/apartment_card.dart';

class FavorisListScreen extends StatefulWidget {
  const FavorisListScreen({super.key});

  @override
  State<FavorisListScreen> createState() => _FavorisListScreenState();
}

class _FavorisListScreenState extends State<FavorisListScreen> {
  List<Chambre> _favoris = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadFavoris();
  }

  Future<void> _loadFavoris() async {
    final favoris = await context.read<ApartmentProvider>().fetchFavorites();
    if (mounted) {
      setState(() {
        _favoris = favoris;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _favoris.isEmpty
              ? const Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.favorite_border, size: 64, color: AppColors.textLight),
                      SizedBox(height: 16),
                      Text('Vous n\'avez pas encore de favoris.'),
                    ],
                  ),
                )
              : GridView.builder(
                  padding: const EdgeInsets.all(12),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    childAspectRatio: 0.75,
                    crossAxisSpacing: 10,
                    mainAxisSpacing: 10,
                  ),
                  itemCount: _favoris.length,
                  itemBuilder: (context, index) {
                    return ApartmentCard(apartment: _favoris[index]);
                  },
                ),
    );
  }
}
