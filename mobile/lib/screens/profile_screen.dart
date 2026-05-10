import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../utils/theme.dart';
import 'reservation_list_screen.dart';
import 'visit_list_screen.dart';
import 'login_screen.dart';
import 'support_screen.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();
    final user = auth.user;
    final bool isAuth = auth.isAuthenticated;

    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              const SizedBox(height: 40), // Espace en haut pour descendre le contenu
              
              // Header Section
              Center(
                child: Column(
                  children: [
                    Container(
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withOpacity(0.1),
                            blurRadius: 20,
                            offset: const Offset(0, 10),
                          ),
                        ],
                      ),
                      child: CircleAvatar(
                        radius: 60,
                        backgroundColor: isAuth ? AppColors.primary.withOpacity(0.1) : Colors.grey[200],
                        backgroundImage: (isAuth && user?['avatar'] != null) 
                            ? NetworkImage(user!['avatar']) 
                            : null,
                        child: (isAuth && user?['avatar'] == null)
                            ? const Icon(Icons.person, size: 60, color: AppColors.primary)
                            : (!isAuth ? const Icon(Icons.person_outline, size: 60, color: Colors.grey) : null),
                      ),
                    ),
                    const SizedBox(height: 20),
                    Text(
                      isAuth ? (user?['name'] ?? 'Utilisateur') : 'Non connecté',
                      style: const TextStyle(
                        fontSize: 26, 
                        fontWeight: FontWeight.w900,
                        color: AppColors.textDark,
                        letterSpacing: -0.5,
                      ),
                    ),
                    if (isAuth) ...[
                      const SizedBox(height: 4),
                      Text(
                        user?['email'] ?? '',
                        style: const TextStyle(color: AppColors.textLight, fontSize: 14),
                      ),
                      const SizedBox(height: 12),
                      // Points de fidélité badge
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                        decoration: BoxDecoration(
                          color: Colors.amber[50],
                          borderRadius: BorderRadius.circular(20),
                          border: Border.all(color: Colors.amber[200]!),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            const Icon(Icons.stars, color: Colors.amber, size: 18),
                            const SizedBox(width: 8),
                            Text(
                              '${user?['client']?['points_fidelite'] ?? 0} points privilège',
                              style: TextStyle(
                                color: Colors.amber[900],
                                fontWeight: FontWeight.bold,
                                fontSize: 13,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ],
                ),
              ),
              
              const SizedBox(height: 40),

              // Menu Options
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24),
                child: Column(
                  children: [
                    if (isAuth) ...[
                      _buildProfileItem(
                        icon: Icons.history_rounded,
                        title: 'Mes Réservations',
                        subtitle: 'Historique et séjours en cours',
                        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ReservationListScreen())),
                      ),
                      _buildProfileItem(
                        icon: Icons.calendar_month_rounded,
                        title: 'Mes Visites',
                        subtitle: 'Suivi de vos demandes de visites',
                        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const VisitListScreen())),
                      ),
                    ],
                    _buildProfileItem(
                      icon: Icons.notifications_none_rounded,
                      title: 'Notifications',
                      subtitle: 'Alertes et messages personnels',
                      onTap: () {},
                    ),
                    _buildProfileItem(
                      icon: Icons.help_outline_rounded,
                      title: 'Aide & Support',
                      subtitle: 'Contactez notre assistance 24/7',
                      onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const SupportScreen())),
                    ),
                    
                    const SizedBox(height: 32),
                    
                    if (isAuth)
                      _buildProfileItem(
                        icon: Icons.logout_rounded,
                        title: 'Déconnexion',
                        subtitle: 'Quitter votre session actuelle',
                        textColor: Colors.red,
                        onTap: () => _showLogoutDialog(context),
                      )
                    else
                      ElevatedButton(
                        onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const LoginScreen())),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: AppColors.primary,
                          foregroundColor: Colors.white,
                          minimumSize: const Size(double.infinity, 60),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(18)),
                          elevation: 8,
                          shadowColor: AppColors.primary.withOpacity(0.3),
                        ),
                        child: const Text(
                          'Se connecter', 
                          style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16),
                        ),
                      ),
                    const SizedBox(height: 40),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildProfileItem({
    required IconData icon, 
    required String title, 
    required String subtitle,
    required VoidCallback onTap,
    Color? textColor,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: ListTile(
        contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
        leading: Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(
            color: (textColor ?? AppColors.primary).withOpacity(0.1),
            borderRadius: BorderRadius.circular(15),
          ),
          child: Icon(icon, color: textColor ?? AppColors.primary, size: 24),
        ),
        title: Text(
          title, 
          style: TextStyle(
            fontWeight: FontWeight.bold,
            fontSize: 16,
            color: textColor ?? AppColors.textDark,
          ),
        ),
        subtitle: Text(
          subtitle,
          style: TextStyle(fontSize: 12, color: Colors.grey[500]),
        ),
        trailing: Icon(Icons.chevron_right_rounded, color: Colors.grey[400]),
        onTap: onTap,
      ),
    );
  }

  void _showLogoutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
        title: const Text('Déconnexion'),
        content: const Text('Souhaitez-vous vraiment vous déconnecter de votre compte ?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context), 
            child: const Text('Rester', style: TextStyle(color: AppColors.textLight))
          ),
          Container(
            margin: const EdgeInsets.only(right: 8),
            child: ElevatedButton(
              onPressed: () {
                context.read<AuthProvider>().logout();
                Navigator.pop(context);
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              child: const Text('Déconnecter'),
            ),
          ),
        ],
      ),
    );
  }
}
