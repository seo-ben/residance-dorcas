import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/apartment_provider.dart';
import '../models/chambre.dart';
import '../utils/theme.dart';
import '../widgets/apartment_card.dart';
import '../widgets/filter_modal.dart';
import '../widgets/state_widgets.dart';
import 'notification_screen.dart';
import '../widgets/global_search_delegate.dart';

class ApartmentListScreen extends StatefulWidget {
  const ApartmentListScreen({super.key});

  @override
  State<ApartmentListScreen> createState() => _ApartmentListScreenState();
}

class _ApartmentListScreenState extends State<ApartmentListScreen> {
  final _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
        context.read<ApartmentProvider>().fetchApartments());
  }

  void _onSearch(String value) {
    context.read<ApartmentProvider>().fetchApartments(filters: {'search': value});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Appartements'),
        centerTitle: false,
        titleTextStyle: const TextStyle(
          color: AppColors.textDark,
          fontSize: 20,
          fontWeight: FontWeight.w900,
          letterSpacing: -0.5,
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.search), 
            onPressed: () {
              showSearch(context: context, delegate: GlobalSearchDelegate());
            },
          ),
          IconButton(
            icon: const Icon(Icons.notifications_none_outlined), 
            onPressed: () {
              Navigator.push(context, MaterialPageRoute(builder: (_) => const NotificationScreen()));
            },
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => context.read<ApartmentProvider>().fetchApartments(),
        color: AppColors.primary,
        child: Consumer<ApartmentProvider>(
          builder: (context, provider, child) {
            if (provider.isLoading) {
              return StateWidgets.loading(message: 'Recherche des meilleurs appartements...');
            }

            if (provider.error != null) {
              return StateWidgets.error(
                message: provider.error!,
                onRetry: () => provider.fetchApartments(),
              );
            }

            if (provider.apartments.isEmpty) {
              return StateWidgets.empty(
                title: 'Aucun résultat',
                message: 'Nous n\'avons trouvé aucun appartement correspondant à vos critères.',
                icon: Icons.search_off_rounded,
              );
            }

            return GridView.builder(
            padding: const EdgeInsets.all(12),
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 2,
              childAspectRatio: 0.75,
              crossAxisSpacing: 10,
              mainAxisSpacing: 10,
            ),
            itemCount: provider.apartments.length,
            itemBuilder: (context, index) {
              return ApartmentCard(apartment: provider.apartments[index]);
            },
          );
          },
        ),
      ),
    );
  }
}
