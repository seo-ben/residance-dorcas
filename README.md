# Résidence Dorcas - Système de Gestion Hôtelière et Immobilière

Bienvenue dans le dépôt de la plateforme **Résidence Dorcas**. Cette application complète permet la gestion de résidences, d'appartements, de réservations, de services additionnels et de locations de véhicules.

## 📱 Application Mobile

L'expérience Résidence Dorcas est également disponible sur mobile ! Vous pouvez télécharger notre application officielle pour gérer vos réservations en déplacement.

[![Disponible sur Google Play](https://play.google.com/intl/en_us/badges/static/images/badges/fr_badge_web_generic.png)](https://play.google.com/store)
*(Lien à mettre à jour avec l'URL réelle du Play Store)*

## 🚀 Fonctionnalités Principales

- **Gestion Immobilière** : Administration des propriétés et des différents types d'appartements (studios, deluxe, suites).
- **Système de Réservation** : Réservations à court et long terme avec gestion des disponibilités en temps réel.
- **Services de Conciergerie** : Commande de services (petit-déjeuner, blanchisserie, navette aéroport, etc.).
- **Location de Véhicules** : Parc automobile disponible à la location avec ou sans chauffeur.
- **Paiements Sécurisés** : Intégration de Stripe et LeekPay pour des transactions fiables.
- **Tableau de Bord Admin** : Statistiques complètes, rapports financiers et gestion des utilisateurs.

## 🛠️ Installation et Déploiement

Suivez ces étapes pour déployer l'application sur votre environnement local ou serveur :

### Prérequis
- PHP 8.1+
- Composer
- Node.js & NPM
- SQLite (ou MySQL selon votre configuration)

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <url-du-depot>
   cd hotelpro
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript**
   ```bash
   npm install
   npm run build
   ```

4. **Configuration de l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Assurez-vous de configurer vos clés API (Stripe, Google, etc.) dans le fichier `.env`.*

5. **Base de données (SQLite par défaut)**
   Le projet est pré-configuré pour utiliser SQLite.
   ```bash
   # Créer le fichier de base de données si nécessaire
   touch database/database.sqlite
   
   # Exécuter les migrations et les données de test (seeds)
   php artisan migrate:fresh --seed
   ```

6. **Lancer le serveur**
   ```bash
   php artisan serve
   ```

## 🏗️ Architecture Technique

- **Backend** : Laravel 10 (PHP)
- **Frontend** : Blade, Livewire, Tailwind CSS
- **Mobile** : Flutter (situé dans le dossier `/mobile`)
- **Base de données** : SQLite / MySQL

## 📄 Licence

Ce projet est la propriété de la Résidence Dorcas. Tous droits réservés.
