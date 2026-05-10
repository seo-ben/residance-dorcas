import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../utils/theme.dart';
import 'home_screen.dart';

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  final List<Map<String, String>> _onboardingData = [
    {
      'title': 'Bienvenue à la Résidance Dorcas',
      'description': 'Découvrez le luxe et le confort au cœur du Togo.',
      'image': 'assets/images/exterieur de la résidence dorcas.jpg',
    },
    {
      'title': 'Services de Conciergerie',
      'description': 'Commandez vos repas, louez un véhicule ou demandez un ménage en un clic.',
      'image': 'assets/images/interieur de la résidences dorcas.webp',
    },
    {
      'title': 'Votre Séjour Idéal',
      'description': 'Réservez votre appartement et profitez d\'un séjour inoubliable.',
      'image': 'assets/images/intérieure de la residence dorcas.webp',
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Stack(
        children: [
          PageView.builder(
            controller: _pageController,
            onPageChanged: (value) => setState(() => _currentPage = value),
            itemCount: _onboardingData.length,
            itemBuilder: (context, index) => _buildPage(index),
          ),
          Positioned(
            bottom: 40,
            left: 24,
            right: 24,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: List.generate(
                    _onboardingData.length,
                    (index) => _buildDot(index),
                  ),
                ),
                ElevatedButton(
                  onPressed: () async {
                    if (_currentPage == _onboardingData.length - 1) {
                      await context.read<AuthProvider>().setOnboardingSeen();
                      if (mounted) {
                        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const HomeScreen()));
                      }
                    } else {
                      _pageController.nextPage(duration: const Duration(milliseconds: 300), curve: Curves.easeIn);
                    }
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  child: Text(_currentPage == _onboardingData.length - 1 ? 'Commencer' : 'Suivant'),
                ),
              ],
            ),
          ),
          Positioned(
            top: 60,
            right: 24,
            child: TextButton(
              onPressed: () async {
                await context.read<AuthProvider>().setOnboardingSeen();
                if (mounted) {
                  Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const HomeScreen()));
                }
              },
              child: const Text('Passer', style: TextStyle(color: AppColors.textLight)),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPage(int index) {
    return Column(
      children: [
        Expanded(
          flex: 3,
          child: Container(
            decoration: BoxDecoration(
              image: DecorationImage(
                image: AssetImage(_onboardingData[index]['image']!),
                fit: BoxFit.cover,
              ),
            ),
          ),
        ),
        Expanded(
          flex: 2,
          child: Padding(
            padding: const EdgeInsets.all(32.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  _onboardingData[index]['title']!,
                  style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: AppColors.textDark),
                ),
                const SizedBox(height: 16),
                Text(
                  _onboardingData[index]['description']!,
                  style: const TextStyle(fontSize: 16, color: AppColors.textLight, height: 1.5),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildDot(int index) {
    return Container(
      height: 8,
      width: _currentPage == index ? 24 : 8,
      margin: const EdgeInsets.only(right: 8),
      decoration: BoxDecoration(
        color: _currentPage == index ? AppColors.primary : Colors.grey[300],
        borderRadius: BorderRadius.circular(4),
      ),
    );
  }
}
