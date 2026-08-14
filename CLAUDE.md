# EPA — Site web du centre de formation

Site vitrine + back-office pour **EPA_BURKINA**, centre de formation professionnelle en Informatique & Action Humanitaire (Burkina Faso, 3 antennes : Ouaga, Bobo, Sahel/Dori).

Le cahier des charges complet et validé est dans [CAHIER_DES_CHARGES.md](CAHIER_DES_CHARGES.md) — s'y référer avant toute décision de scope ou de fonctionnalité. La section 8 de ce document liste les points encore ouverts avec le client (charte graphique définitive, chiffres réels, modèle de certificat, etc.) : les vérifier avant de coder les fonctionnalités concernées.

## Charte graphique (provisoire, déduite du logo)

Couleurs échantillonnées directement sur `logo.jpeg`, déclarées dans `tailwind.config.js` sous `theme.extend.colors.epa` :

| Nom | Hex | Usage |
|---|---|---|
| `epa-red` | `#EE0916` | Couleur principale (CTA, liens actifs, accents) |
| `epa-black` | `#1A1A1A` | Texte |
| `epa-green` / `epa-purple` / `epa-magenta` / `epa-orange` / `epa-blue` | voir config | Accents ponctuels (badges programme, icônes), à utiliser avec parcimonie — ne pas en faire des couleurs de fond dominantes |
| `epa-gray` | `#57585C` | Texte secondaire |

Cette palette n'est pas validée par le client (voir section 8 du cahier des charges) — centralisée ici pour être facile à ajuster en un seul endroit le jour où la charte définitive arrive.

## Stack technique

- **Back-end :** PHP / Laravel
- **Base de données :** MySQL
- **Front-end :** Blade + Alpine.js pour l'interactivité légère (pas de SPA JS lourd)
- **Environnement local :** XAMPP (Apache/MySQL/PHP)
- **Hébergement cible :** VPS
- **Langues :** FR (par défaut) + EN — tout contenu géré en back-office doit être saisissable dans les deux langues

## Contenu source

Le dossier `doc/` contient la documentation fournie par le client à utiliser comme données de départ :
- `logo.jpeg` — logo officiel EPA
- Catalogues/flyers 2026 (images) — présentation, programmes, formations, coordonnées
- `INSCRIPTIONS EN LIGNE EPA (1).xlsx` — export du formulaire Google Forms actuellement utilisé, sert de référence pour les champs du formulaire d'inscription

## Principes de conception

- Les **programmes de formation** et les **formations** sont des entités gérées en base (CRUD back-office), jamais codées en dur — le client doit pouvoir ajouter un programme (ex: "Gestion des Entreprises") sans intervention développeur.
- Le **back-office** est le seul moyen prévu pour l'équipe EPA de modifier le contenu (formations, sessions, actualités, équipe, partenaires, certificats, codes promo, chiffres clés).
- Un seul rôle admin au lancement (pas de permissions différenciées par antenne pour l'instant).
- Pas de paiement en ligne au lancement (phase 2 potentielle, mobile money évoqué).
- Champs bilingues : convention de colonnes suffixées `_fr` / `_en` (pas de package de traduction externe), pour rester simple et explicite.

## État actuel du projet

Squelette Laravel 12 en place avec le modèle de données complet (migrations + modèles Eloquent) : `Antenne`, `Programme`, `Formation` (+ pivot `formation_antenne`), `FormationSession`, `Candidature`, `Partenaire`, `PromoCode`, `Actualite`, `TeamMember`, `Certificate`, `KeyStat`, `Setting`.

**Back-office (Phase 1) fonctionnel** : authentification (Laravel Breeze, sans auto-inscription — comptes créés manuellement), layout `/admin` avec sidebar (`resources/views/layouts/admin.blade.php` + composant `x-admin-layout`), CRUD complets et testés bout-en-bout pour Antennes, Programmes, Formations (upload image, liaison multi-antennes, champs bilingues FR/EN, publication) et Sessions de formation.

Reste à faire (Phase 2+) : site public (accueil, fiches formation, inscription), CRUD back-office pour Candidatures/Actualités/Équipe/Partenaires/Certificats/Codes promo/Chiffres clés, emails, génération PDF/QR code, traductions EN réelles (actuellement `title_en` = copie de `title_fr` dans le seed).

### Environnement local

- Base de données : MySQL `epa_db` (XAMPP, `root` sans mot de passe)
- Lancer le serveur : `php artisan serve`
- Compte admin de départ : `admin@epa.local` / `changeme123` (**à changer avant toute mise en production**)
- Données de démarrage : `php artisan db:seed` (3 antennes, 2 programmes, 28 formations — non publiées tant que les contenus définitifs ne sont pas fournis)
