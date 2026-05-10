import 'package:flutter/material.dart';
import '../utils/theme.dart';
import 'package:url_launcher/url_launcher.dart';

class SupportScreen extends StatelessWidget {
  const SupportScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Support & Contact')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Center(
              child: Icon(Icons.support_agent, size: 80, color: AppColors.primary),
            ),
            const SizedBox(height: 24),
            const Center(
              child: Text(
                'Comment pouvons-nous vous aider ?',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
              ),
            ),
            const SizedBox(height: 12),
            const Center(
              child: Text(
                'Notre équipe est disponible 24h/24 et 7j/7 pour répondre à vos questions.',
                textAlign: TextAlign.center,
                style: TextStyle(color: AppColors.textLight),
              ),
            ),
            const SizedBox(height: 40),
            _buildContactCard(
              icon: Icons.phone,
              title: 'Appelez-nous',
              subtitle: '+228 90 14 99 18',
              color: Colors.blue,
              onTap: () => _launchUrl('tel:+22890149918'),
            ),
            const SizedBox(height: 16),
            _buildContactCard(
              icon: Icons.message,
              title: 'WhatsApp',
              subtitle: 'Discutez avec un agent',
              color: Colors.green,
              onTap: () => _launchUrl('https://wa.me/22890149918'),
            ),
            const SizedBox(height: 16),
            _buildContactCard(
              icon: Icons.email,
              title: 'Email',
              subtitle: 'contact@residancedorcas.com',
              color: Colors.orange,
              onTap: () => _launchUrl('mailto:contact@residancedorcas.com'),
            ),
            const SizedBox(height: 32),
            const Text('Questions fréquentes', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            _buildFaqItem('Comment annuler ma réservation ?', 'Vous pouvez annuler votre réservation directement depuis votre profil jusqu\'à 48h avant.'),
            _buildFaqItem('Quels sont les modes de paiement ?', 'Nous acceptons les cartes bancaires, Mobile Money (Orange, MTN, Moov) via Leekpay.'),
          ],
        ),
      ),
    );
  }

  Widget _buildContactCard({
    required IconData icon,
    required String title,
    required String subtitle,
    required Color color,
    required VoidCallback onTap,
  }) {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: color.withOpacity(0.1),
          child: Icon(icon, color: color),
        ),
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text(subtitle),
        trailing: const Icon(Icons.chevron_right),
        onTap: onTap,
      ),
    );
  }

  Widget _buildFaqItem(String question, String answer) {
    return ExpansionTile(
      title: Text(question, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w500)),
      children: [
        Padding(
          padding: const EdgeInsets.all(16.0),
          child: Text(answer, style: const TextStyle(color: AppColors.textLight)),
        ),
      ],
    );
  }

  Future<void> _launchUrl(String url) async {
    final Uri uri = Uri.parse(url);
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri);
    }
  }
}
