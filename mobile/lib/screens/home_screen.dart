import 'package:flutter/material.dart';
import 'dart:async';
import '../utils/theme.dart';

import 'apartment_list_screen.dart';
import 'login_screen.dart';
import 'profile_screen.dart';
import 'favoris_list_screen.dart';
import 'vehicle_list_screen.dart';
import 'service_list_screen.dart';
import 'visit_request_screen.dart';
import 'support_screen.dart';
import 'notification_screen.dart';
import '../providers/auth_provider.dart';
import '../providers/property_provider.dart';
import '../widgets/state_widgets.dart';
import '../config/api_config.dart';
import 'package:provider/provider.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../widgets/global_search_delegate.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _selectedIndex = 0;

  late final List<Widget> _screens;

  @override
  void initState() {
    super.initState();
    _screens = [
      HomeContent(onTabChange: (index) {
        setState(() => _selectedIndex = index);
      }),
      const ApartmentListScreen(),
      const VehicleListScreen(),
      const ServiceListScreen(),
      const ProfileScreen(),
    ];
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: IndexedStack(
        index: _selectedIndex,
        children: _screens,
      ),
      bottomNavigationBar: Container(
        height: 70, // Hauteur réduite car plus de bouton central
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
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            _buildNavItem(0, Icons.home_filled, 'Accueil'),
            _buildNavItem(1, Icons.apartment_outlined, 'Apparts'),
            _buildNavItem(2, Icons.directions_car_outlined, 'Voitures'),
            _buildNavItem(3, Icons.room_service_outlined, 'Services'),
            _buildNavItem(4, Icons.person_outline, 'Profil'),
          ],
        ),
      ),
    );
  }

  String _getAppBarTitle() {
    switch (_selectedIndex) {
      case 0:
        return 'Résidence Dorcas';
      case 1:
        return 'Appartements';
      case 2:
        return 'Location de Voitures';
      case 3:
        return 'Nos Services';
      case 4:
        return 'Mon Profil';
      default:
        return 'Résidence Dorcas';
    }
  }

  Widget _buildNavItem(int index, IconData icon, String label) {
    bool isSelected = _selectedIndex == index;
    return InkWell(
      onTap: () => setState(() => _selectedIndex = index),
      child: SizedBox(
        width: 70,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              icon,
              color: isSelected ? AppColors.primary : AppColors.textLight,
              size: 26,
            ),
            const SizedBox(height: 4),
            Text(
              label,
              style: TextStyle(
                fontSize: 10,
                color: isSelected ? AppColors.primary : AppColors.textLight,
                fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class HomeContent extends StatefulWidget {
  final Function(int)? onTabChange;
  const HomeContent({super.key, this.onTabChange});

  @override
  State<HomeContent> createState() => _HomeContentState();
}

class _HomeContentState extends State<HomeContent> {
  final PageController _pageController = PageController();
  int _currentPage = 0;
  Timer? _timer;
  final List<Map<String, String>> _bannerData = [
    {
      'image': 'assets/images/exterieur de la résidence dorcas.jpg',
      'title': 'Votre confort,\nnotre priorité',
      'subtitle': 'Des résidences d\'exception,\ndes services sur mesure.',
    },
    {
      'image': 'assets/images/entrée de la residence dorcas.webp',
      'title': 'Séjour de luxe\nà votre portée',
      'subtitle': 'Profitez d\'un cadre unique et d\'un service premium.',
    },
    {
      'image': 'assets/images/interierur de la residence dorcas.webp',
      'title': 'Des services\npour vous servir',
      'subtitle': 'Ménage, restauration et conciergerie 24h/24.',
    },
    {
      'image': 'assets/images/allé de la residence.webp',
      'title': 'Cadre Paisible',
      'subtitle': 'Un environnement calme pour vos séjours.',
    },
    {
      'image': 'assets/images/cuisine de la residences dorcas.webp',
      'title': 'Cuisine Équipée',
      'subtitle': 'Tout le confort nécessaire pour cuisiner.',
    },
  ];

  @override
  void initState() {
    super.initState();
    Future.microtask(() => context.read<PropertyProvider>().fetchProperties());
    _startAutoPlay();
  }

  @override
  void dispose() {
    _timer?.cancel();
    _pageController.dispose();
    super.dispose();
  }

  void _startAutoPlay() {
    _timer = Timer.periodic(const Duration(seconds: 4), (Timer timer) {
      if (mounted) {
        setState(() {
          _currentPage = (_currentPage + 1) % _bannerData.length;
        });
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: RefreshIndicator(
        onRefresh: () async {
          await context.read<PropertyProvider>().fetchProperties();
        },
        color: AppColors.primary,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildMainBanner(),
              Transform.translate(
                offset: const Offset(0, -30),
                child: Container(
                  width: double.infinity,
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.vertical(top: Radius.circular(32)),
                  ),
                  child: Column(
                    children: [
                      const SizedBox(height: 24),
                      _buildCategories(),
                      const SizedBox(height: 32),
                      _buildSectionHeader('Résidences populaires', onSeeAll: () => widget.onTabChange?.call(1)),
                      const SizedBox(height: 16),
                      _buildPopularResidences(),
                      const SizedBox(height: 32),
                      _buildSectionHeader('Services à la demande', onSeeAll: () => widget.onTabChange?.call(2)),
                      const SizedBox(height: 16),
                      _buildServicesGrid(),
                      const SizedBox(height: 32),
                      _buildCarRentalBanner(),
                      const SizedBox(height: 32),
                      _buildSectionHeader('Mes favoris', onSeeAll: () => widget.onTabChange?.call(3)),
                      const SizedBox(height: 40), // Spacing adjusted
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildHeader() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20.0, vertical: 10.0),
      child: Row(
        children: [
          Container(
            width: 45,
            height: 45,
            decoration: const BoxDecoration(
              shape: BoxShape.circle,
              image: DecorationImage(
                image: AssetImage('assets/images/Residence Dorcas logo.jpg'),
                fit: BoxFit.cover,
              ),
            ),
          ),
          const SizedBox(width: 12),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Résidence Dorcas',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: AppColors.textDark,
                ),
              ),
              Row(
                children: [
                  Text(
                    'Bienvenue chez vous',
                    style: TextStyle(
                      fontSize: 13,
                      color: AppColors.textLight.withOpacity(0.8),
                    ),
                  ),
                  const SizedBox(width: 4),
                  const Text('😊', style: TextStyle(fontSize: 13)),
                ],
              ),
            ],
          ),
          const Spacer(),
          Stack(
            children: [
              const Icon(Icons.notifications_none_outlined, size: 28),
              Positioned(
                right: 4,
                top: 4,
                child: Container(
                  width: 8,
                  height: 8,
                  decoration: const BoxDecoration(
                    color: AppColors.primary,
                    shape: BoxShape.circle,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMainBanner() {
    final currentData = _bannerData[_currentPage];
    return SizedBox(
      height: 300,
      width: double.infinity,
      child: Stack(
        children: [
          // Background Layer with Image & Dark Gradient & Bottom Radius
          AnimatedSwitcher(
            duration: const Duration(milliseconds: 1000),
            child: Container(
              key: ValueKey<int>(_currentPage),
              width: double.infinity,
              height: double.infinity,
              decoration: BoxDecoration(
                borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(40),
                  bottomRight: Radius.circular(40),
                ),
                image: DecorationImage(
                  image: AssetImage(currentData['image']!),
                  fit: BoxFit.cover,
                ),
              ),
              child: Container(
                decoration: BoxDecoration(
                  borderRadius: const BorderRadius.only(
                    bottomLeft: Radius.circular(40),
                    bottomRight: Radius.circular(40),
                  ),
                  gradient: LinearGradient(
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                    colors: [
                      Colors.black.withOpacity(0.2),
                      Colors.transparent,
                      Colors.black.withOpacity(0.5),
                    ],
                  ),
                ),
              ),
            ),
          ),

          // Floating Top Navbar Overlay (with enough space)
          Positioned(
            top: MediaQuery.of(context).padding.top + 10,
            left: 20,
            right: 20,
            child: Row(
              children: [
                Container(
                  width: 42,
                  height: 42,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    border: Border.all(color: Colors.white24, width: 1),
                    image: const DecorationImage(
                      image: AssetImage('assets/images/Residence Dorcas logo.jpg'),
                      fit: BoxFit.cover,
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                const Text(
                  'Dorcas',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                    fontWeight: FontWeight.w900,
                    letterSpacing: 1,
                    shadows: [
                      Shadow(offset: Offset(0, 1), blurRadius: 4, color: Colors.black54),
                    ],
                  ),
                ),
                const Spacer(),
                _buildCircleAction(Icons.search, () {
                  showSearch(
                    context: context,
                    delegate: GlobalSearchDelegate(),
                  );
                }),
                const SizedBox(width: 10),
                _buildCircleAction(Icons.notifications_none_outlined, () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const NotificationScreen()),
                  );
                }, hasNotification: true),
              ],
            ),
          ),
          
          // Page Indicators
          Positioned(
            bottom: 50,
            left: 0,
            right: 0,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(_bannerData.length, (index) {
                return AnimatedContainer(
                  duration: const Duration(milliseconds: 300),
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  height: 6,
                  width: _currentPage == index ? 24 : 6,
                  decoration: BoxDecoration(
                    color: _currentPage == index ? Colors.white : Colors.white.withOpacity(0.5),
                    borderRadius: BorderRadius.circular(3),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.2),
                        blurRadius: 2,
                        offset: const Offset(0, 1),
                      ),
                    ],
                  ),
                );
              }),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCircleAction(IconData icon, VoidCallback onTap, {bool hasNotification = false}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 40,
        height: 40,
        decoration: BoxDecoration(
          color: Colors.black.withOpacity(0.2),
          shape: BoxShape.circle,
          border: Border.all(color: Colors.white24, width: 1),
        ),
        child: Stack(
          alignment: Alignment.center,
          children: [
            Icon(icon, color: Colors.white, size: 22),
            if (hasNotification)
              Positioned(
                right: 10,
                top: 10,
                child: Container(
                  width: 8,
                  height: 8,
                  decoration: const BoxDecoration(
                    color: AppColors.primary,
                    shape: BoxShape.circle,
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildCategories() {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Row(
        children: [
          _categoryItem(Icons.home_outlined, 'Résidences', () => widget.onTabChange?.call(1)),
          _categoryItem(Icons.bed_outlined, 'Chambres', () => widget.onTabChange?.call(1)),
          _categoryItem(Icons.room_service_outlined, 'Services', () => widget.onTabChange?.call(3)),
          _categoryItem(Icons.directions_car_outlined, 'Voitures', () => widget.onTabChange?.call(2)),
          _categoryItem(Icons.grid_view_outlined, 'Plus', () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (_) => const SupportScreen()),
            );
          }),
        ],
      ),
    );
  }

  Widget _categoryItem(IconData icon, String label, VoidCallback onTap) {
    return Padding(
      padding: const EdgeInsets.only(right: 24.0),
      child: GestureDetector(
        onTap: onTap,
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: AppColors.primary.withOpacity(0.05),
                shape: BoxShape.circle,
                border: Border.all(color: AppColors.primary.withOpacity(0.1), width: 1),
              ),
              child: Icon(icon, color: AppColors.primary, size: 28),
            ),
            const SizedBox(height: 10),
            Text(
              label,
              textAlign: TextAlign.center,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                fontSize: 12,
                color: AppColors.textDark,
                fontWeight: FontWeight.w500,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title, {VoidCallback? onSeeAll}) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            title,
            style: const TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: AppColors.textDark,
            ),
          ),
          GestureDetector(
            onTap: onSeeAll,
            child: Row(
              children: [
                const Text(
                  'Voir tout',
                  style: TextStyle(
                    fontSize: 12,
                    color: AppColors.primary,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(width: 4),
                const Icon(Icons.chevron_right, size: 16, color: AppColors.primary),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPopularResidences() {
    return Consumer<PropertyProvider>(
      builder: (context, provider, child) {
        if (provider.isLoading) {
          return const SizedBox(
            height: 200,
            child: Center(child: CircularProgressIndicator()),
          );
        }
        return SizedBox(
          height: 230,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.only(left: 20, bottom: 10),
            itemCount: provider.properties.length,
            itemBuilder: (context, index) {
              final property = provider.properties[index];
              return GestureDetector(
                onTap: () => widget.onTabChange?.call(1),
                child: Container(
                  width: 220,
                  margin: const EdgeInsets.only(right: 16),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(24),
                    color: Colors.white,
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.04),
                        blurRadius: 15,
                        offset: const Offset(0, 8),
                      ),
                    ],
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Stack(
                        children: [
                          ClipRRect(
                            borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
                            child: CachedNetworkImage(
                              imageUrl: property.image ?? '',
                              height: 130,
                              width: double.infinity,
                              fit: BoxFit.cover,
                              errorWidget: (context, url, error) => Image.asset(
                                'assets/images/exterieur de la résidence dorcas.png',
                                height: 130,
                                width: double.infinity,
                                fit: BoxFit.cover,
                              ),
                            ),
                          ),
                          Positioned(
                            right: 12,
                            top: 12,
                            child: Container(
                              padding: const EdgeInsets.all(8),
                              decoration: const BoxDecoration(
                                color: Colors.white,
                                shape: BoxShape.circle,
                              ),
                              child: const Icon(Icons.favorite_border, size: 18, color: Colors.grey),
                            ),
                          ),
                        ],
                      ),
                      Padding(
                        padding: const EdgeInsets.all(12.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              property.nom,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 14,
                                color: AppColors.textDark,
                              ),
                            ),
                            const SizedBox(height: 6),
                            Row(
                              children: [
                                Icon(Icons.location_on, size: 14, color: Colors.grey.shade400),
                                const SizedBox(width: 4),
                                Expanded(
                                  child: Text(
                                    property.adresse,
                                    maxLines: 1,
                                    overflow: TextOverflow.ellipsis,
                                    style: TextStyle(
                                      color: Colors.grey.shade500,
                                      fontSize: 12,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              );
            },
          ),
        );
      },
    );
  }

  Widget _buildServicesGrid() {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      padding: const EdgeInsets.symmetric(horizontal: 20),
      child: Row(
        children: [
          _serviceCard(Icons.cleaning_services_outlined, 'Ménage', () => widget.onTabChange?.call(2)),
          _serviceCard(Icons.local_laundry_service_outlined, 'Blanchisserie', () => widget.onTabChange?.call(2)),
          _serviceCard(Icons.room_service_outlined, 'Restauration', () => widget.onTabChange?.call(2)),
          _serviceCard(Icons.build_outlined, 'Maintenance', () => widget.onTabChange?.call(2)),
        ],
      ),
    );
  }

  Widget _serviceCard(IconData icon, String label, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 110,
        height: 100,
        margin: const EdgeInsets.only(right: 12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.grey.shade100, width: 1.5),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 8,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: AppColors.primary.withOpacity(0.9), size: 28),
            const SizedBox(height: 8),
            Text(
              label,
              textAlign: TextAlign.center,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                fontSize: 12,
                fontWeight: FontWeight.w600,
                color: AppColors.textDark,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCarRentalBanner() {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20),
      height: 170,
      width: double.infinity,
      decoration: BoxDecoration(
        color: const Color(0xFFF9F9F9),
        borderRadius: BorderRadius.circular(24),
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(24),
        child: Stack(
          clipBehavior: Clip.antiAlias,
          children: [
            // Cercle rouge décoratif en fond
            Positioned(
              right: -80,
              top: -20,
              bottom: -20,
              child: Container(
                width: 200,
                decoration: BoxDecoration(
                  color: AppColors.primary,
                  shape: BoxShape.circle,
                ),
              ),
            ),
            // Image de la voiture poussée vers la droite
            Positioned(
              right: -60,
              bottom: -10,
              top: -10,
              child: Image.asset(
                'lib/assets/images/voitures/voiture.png',
                width: 280,
                fit: BoxFit.contain,
              ),
            ),
            // Contenu textuel à gauche, centré verticalement
            Align(
              alignment: Alignment.centerLeft,
              child: Padding(
                padding: const EdgeInsets.only(left: 20, right: 140),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Text(
                      'Louez une voiture\nen toute simplicité',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        height: 1.2,
                        color: AppColors.textDark,
                      ),
                    ),
                    const SizedBox(height: 6),
                    const Text(
                      'Confort, sécurité et liberté.',
                      style: TextStyle(fontSize: 11, color: AppColors.textLight),
                    ),
                    const SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: () => widget.onTabChange?.call(2),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        foregroundColor: Colors.white,
                        elevation: 0,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                        minimumSize: Size.zero,
                      ),
                      child: const Text(
                        'Réserver',
                        style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
