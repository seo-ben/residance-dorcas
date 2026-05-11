import 'package:flutter/material.dart';
import 'dart:async';
import 'package:provider/provider.dart';
import '../providers/vehicle_provider.dart';
import '../utils/theme.dart';
import 'package:cached_network_image/cached_network_image.dart';

import 'vehicle_booking_screen.dart';
import 'notification_screen.dart';
import '../widgets/global_search_delegate.dart';

class VehicleListScreen extends StatefulWidget {
  final bool showAppBar;
  const VehicleListScreen({super.key, this.showAppBar = false});

  @override
  State<VehicleListScreen> createState() => _VehicleListScreenState();
}

class _VehicleListScreenState extends State<VehicleListScreen> {
  final PageController _pageController = PageController();
  Timer? _carouselTimer;
  int _currentHeroPage = 0;

  @override
  void initState() {
    super.initState();
    Future.microtask(() => context.read<VehicleProvider>().fetchVehicles());
  }

  @override
  void dispose() {
    _carouselTimer?.cancel();
    _pageController.dispose();
    super.dispose();
  }

  void _startCarouselTimer(int count) {
    _carouselTimer?.cancel();
    if (count <= 1) return;
    
    _carouselTimer = Timer.periodic(const Duration(seconds: 4), (timer) {
      if (_pageController.hasClients) {
        _currentHeroPage = (_currentHeroPage + 1) % count;
        _pageController.animateToPage(
          _currentHeroPage,
          duration: const Duration(milliseconds: 800),
          curve: Curves.easeInOutCubic,
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Location Voitures'),
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
      body: Consumer<VehicleProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading) return const Center(child: CircularProgressIndicator());
          
          if (provider.vehicles.isEmpty) {
            return const Center(child: Text('Aucun véhicule disponible pour le moment.'));
          }

          final heroVehicles = provider.vehicles.take(3).toList();
          final otherVehicles = provider.vehicles.skip(3).toList();

          // Start timer only once or when count changes
          WidgetsBinding.instance.addPostFrameCallback((_) {
            if (_carouselTimer == null || !(_carouselTimer!.isActive)) {
              _startCarouselTimer(heroVehicles.length);
            }
          });

          return CustomScrollView(
            slivers: [
              // Hero Carousel Section
              SliverToBoxAdapter(
                child: _buildHeroCarousel(heroVehicles),
              ),
              
              // Title Section
              const SliverToBoxAdapter(
                child: Padding(
                  padding: EdgeInsets.fromLTRB(16, 24, 16, 16),
                  child: Text(
                    'Nos Véhicules',
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: AppColors.textDark,
                    ),
                  ),
                ),
              ),

              // Grid Section (2 per line)
              SliverPadding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                sliver: SliverGrid(
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    crossAxisSpacing: 12,
                    mainAxisSpacing: 12,
                    childAspectRatio: 0.75,
                  ),
                  delegate: SliverChildBuilderDelegate(
                    (context, index) {
                      final vehicle = provider.vehicles[index];
                      return _buildVehicleCard(vehicle, index);
                    },
                    childCount: provider.vehicles.length,
                  ),
                ),
              ),
              const SliverToBoxAdapter(child: SizedBox(height: 24)),
            ],
          );
        },
      ),
    );
  }

  final List<String> _placeholderImages = [
    'assets/images/voiture/voiture.jpg',
    'assets/images/voiture/images.jpg',
    'assets/images/voiture/images (1).jpg',
    'assets/images/voiture/images (2).jpg',
    'assets/images/voiture/téléchargement.jpg',
    'assets/images/voiture/téléchargement (1).jpg',
    'assets/images/voiture/téléchargement (2).jpg',
  ];

  Widget _buildHeroCarousel(List<dynamic> vehicles) {
    return SizedBox(
      height: 280,
      child: PageView.builder(
        controller: _pageController,
        onPageChanged: (index) => _currentHeroPage = index,
        itemCount: vehicles.length,
        itemBuilder: (context, index) {
          final vehicle = vehicles[index];
          return GestureDetector(
            onTap: () => _navigateToBooking(vehicle),
            child: Stack(
              children: [
                // Background Layer with Image & Gradient
                Container(
                  width: double.infinity,
                  margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(24),
                    image: DecorationImage(
                      image: AssetImage(_placeholderImages[index % _placeholderImages.length]),
                      fit: BoxFit.cover,
                      colorFilter: ColorFilter.mode(
                        Colors.black.withOpacity(0.7),
                        BlendMode.darken,
                      ),
                    ),
                  ),
                ),
                
                // Main Content Layer
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 24),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          color: AppColors.primary.withOpacity(0.9),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: const Text(
                          'PREMIUM',
                          style: TextStyle(
                            color: Colors.white, 
                            fontSize: 10, 
                            fontWeight: FontWeight.w900,
                            letterSpacing: 1.5,
                          ),
                        ),
                      ),
                      const SizedBox(height: 12),
                      Text(
                        '${vehicle.marque}\n${vehicle.modele}',
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 26,
                          fontWeight: FontWeight.w900,
                          height: 1.1,
                        ),
                      ),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          const Icon(Icons.flash_on, color: Colors.amber, size: 16),
                          const SizedBox(width: 4),
                          Text(
                            'À partir de ${vehicle.prixJournalier.toStringAsFixed(0)} F / jour',
                            style: const TextStyle(
                              color: Colors.white70,
                              fontSize: 14,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 20),
                      ElevatedButton(
                        onPressed: () => _navigateToBooking(vehicle),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.white,
                          foregroundColor: Colors.black,
                          elevation: 0,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                        ),
                        child: const Text('RÉSERVER MAINTENANT', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 10)),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildPlaceholder({int? index}) {
    // Use an index to pick a specific placeholder or random
    final imagePath = _placeholderImages[(index ?? 0) % _placeholderImages.length];
    
    return Stack(
      fit: StackFit.expand,
      children: [
        Image.asset(
          imagePath,
          fit: BoxFit.cover,
        ),
        // Dark overlay to make the brand logo/placeholder look better
        Container(
          decoration: BoxDecoration(
            gradient: LinearGradient(
              begin: Alignment.topCenter,
              end: Alignment.bottomCenter,
              colors: [
                Colors.transparent,
                Colors.black.withOpacity(0.3),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildVehicleCard(dynamic vehicle, int index) {
    return GestureDetector(
      onTap: () => _navigateToBooking(vehicle),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: ClipRRect(
                borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                child: vehicle.image != null
                    ? CachedNetworkImage(
                        imageUrl: vehicle.image!,
                        width: double.infinity,
                        fit: BoxFit.cover,
                        errorWidget: (context, url, error) => _buildPlaceholder(index: index),
                      )
                    : _buildPlaceholder(index: index),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(10.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    '${vehicle.marque} ${vehicle.modele}',
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                      color: AppColors.textDark,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    '${vehicle.prixJournalier.toStringAsFixed(0)} FCFA',
                    style: const TextStyle(
                      color: AppColors.primary,
                      fontWeight: FontWeight.bold,
                      fontSize: 13,
                    ),
                  ),
                  const SizedBox(height: 8),
                  SizedBox(
                    width: double.infinity,
                    height: 32,
                    child: ElevatedButton(
                      onPressed: () => _navigateToBooking(vehicle),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        foregroundColor: Colors.white,
                        padding: EdgeInsets.zero,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        elevation: 0,
                      ),
                      child: const Text(
                        'Réserver',
                        style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _navigateToBooking(dynamic vehicle) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => VehicleBookingScreen(vehicle: vehicle),
      ),
    );
  }
}
