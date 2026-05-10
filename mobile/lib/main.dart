import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'utils/theme.dart';
import 'screens/home_screen.dart';
import 'screens/onboarding_screen.dart';
import 'providers/auth_provider.dart';
import 'providers/apartment_provider.dart';
import 'providers/property_provider.dart';
import 'providers/booking_provider.dart';
import 'providers/service_provider.dart';
import 'providers/vehicle_provider.dart';
import 'providers/visit_provider.dart';

void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
        ChangeNotifierProvider(create: (_) => ApartmentProvider()),
        ChangeNotifierProvider(create: (_) => PropertyProvider()),
        ChangeNotifierProvider(create: (_) => BookingProvider()),
        ChangeNotifierProvider(create: (_) => ServiceProvider()),
        ChangeNotifierProvider(create: (_) => VehicleProvider()),
        ChangeNotifierProvider(create: (_) => VisitProvider()),
      ],
      child: const ResidanceDorcasApp(),
    ),
  );
}

class ResidanceDorcasApp extends StatelessWidget {
  const ResidanceDorcasApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Résidance Dorcas',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      home: Consumer<AuthProvider>(
        builder: (context, auth, _) {
          if (auth.isInitializing) {
            return const Scaffold(
              body: Center(
                child: CircularProgressIndicator(color: AppColors.primary),
              ),
            );
          }
          if (!auth.hasSeenOnboarding) {
            return const OnboardingScreen();
          }
          return const HomeScreen();
        },
      ),
    );
  }
}
