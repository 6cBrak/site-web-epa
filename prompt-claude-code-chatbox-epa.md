# Prompt de démarrage — Chatbox assistant IA sur epa-bf.com

À coller directement dans Claude Code, à la racine du projet Laravel du site epa-bf.com.

---

## Contexte

Ce projet est le site web Laravel d'EPA_BURKINA (centre de formation professionnelle). Il contient déjà les pages publiques (accueil, formations, contact, inscription) et un modèle de données pour les formations (nom, prix, durée, antenne, etc.), affiché sur `/formations`.

**Objectif de cette tâche :** ajouter un widget de chat IA sur le site, capable de répondre aux questions des visiteurs sur les formations à partir des données déjà existantes dans le site, et de capturer les leads (nom + téléphone/email) quand un visiteur les fournit spontanément dans la conversation.

**Ce projet reste volontairement autonome** : aucune connexion à un système externe (pas de logiciel de gestion interne, pas d'API tierce autre que l'API Claude). Tout ce dont le widget a besoin existe déjà dans ce projet ou sera créé dans ce projet.

---

## Étape 0 — Explorer avant de coder

Avant toute chose : inspecte le modèle existant qui alimente la page `/formations` (probablement `Formation` ou similaire), sa migration, et le contrôleur qui l'affiche. Ne devine pas la structure des champs — lis le code existant pour connaître les noms exacts des colonnes (prix, durée, antenne, pôle, prérequis, etc.).

Vérifie aussi comment le layout principal du site est structuré (fichier Blade parent, ex: `resources/views/layouts/app.blade.php`) pour savoir où insérer le widget proprement.

---

## Étape 1 — Configuration

- Ajoute `ANTHROPIC_API_KEY` dans `.env` et `.env.example` (valeur vide dans `.env.example`)
- Vérifie qu'un client HTTP est disponible pour appeler l'API Claude (`Illuminate\Support\Facades\Http` suffit, pas besoin de SDK externe)

---

## Étape 2 — Base de connaissances (lecture des formations existantes)

Crée un service (ex: `app/Services/AssistantKnowledgeService.php`) qui :
- Lit toutes les formations actives depuis le modèle existant identifié à l'étape 0
- Compile ces données en un texte structuré, clair, utilisable comme contexte système pour Claude (nom, pôle, prix, durée, antenne(s) disponibles, prérequis)
- Met ce texte en cache (`Cache::remember`, durée raisonnable, ex: 1 heure) pour éviter de reconstruire ce contexte à chaque message
- Inclut aussi les informations fixes du site : les 3 antennes (Ouaga siège, Bobo, Dori/Sahel) avec leurs coordonnées, visibles dans le footer actuel du site

---

## Étape 3 — Tables locales pour les conversations

Crée trois migrations :
- `assistant_conversations` — `id`, `session_id` (identifiant anonyme du visiteur, ex: UUID stocké côté client), `created_at`, `updated_at`
- `assistant_messages` — `id`, `conversation_id` (FK), `role` (`user` ou `assistant`), `content` (text), `created_at`
- `assistant_leads_captures` — `id`, `conversation_id` (FK), `name`, `contact` (téléphone ou email), `formation_interest` (nullable, texte libre), `captured_at`, `status` (défaut `nouveau`)

---

## Étape 4 — Endpoint backend

Crée `app/Http/Controllers/AssistantController.php` avec une méthode `handleMessage(Request $request)` :
- Reçoit `session_id` et `message` du widget
- Récupère ou crée la conversation correspondante, charge l'historique récent (limiter à ~10 derniers messages pour rester efficace)
- Appelle l'API Claude (modèle `claude-sonnet-4-6` ou équivalent courant) avec le contexte système de l'étape 2 + l'historique + le nouveau message
- Dans le prompt système, demande explicitement à Claude de signaler dans sa réponse (via un format structuré simple, ex: un bloc JSON en fin de réponse ou un tool call) si le visiteur vient de fournir un nom et un contact (téléphone/email) — cela permet à Laravel de déclencher la capture du lead sans regex fragile
- Sauvegarde le message utilisateur et la réponse dans `assistant_messages`
- Si un lead est détecté, l'enregistre dans `assistant_leads_captures`
- Retourne la réponse en JSON au widget

Ajoute la route dans `routes/api.php` :
```php
Route::post('/assistant/message', [AssistantController::class, 'handleMessage']);
```
Ajoute un rate limiting raisonnable sur cette route (ex: `throttle:20,1`) pour éviter les abus.

---

## Étape 5 — Widget frontend

Crée `resources/views/partials/assistant-widget.blade.php` :
- Bulle de chat flottante en bas à droite, discrète, cohérente avec le design actuel du site (vérifie les couleurs/police déjà utilisées avant d'improviser un style)
- JavaScript vanilla (ou Alpine.js si déjà présent dans le projet — vérifie avant d'en ajouter une nouvelle dépendance) qui :
  - Génère un `session_id` (UUID) stocké en mémoire JS pour la durée de la session de navigation (pas de localStorage, voir contrainte ci-dessous)
  - Envoie les messages à `/api/assistant/message`
  - Affiche la conversation dans une interface simple (bulle utilisateur / bulle assistant)
- Inclus ce partial dans le layout principal identifié à l'étape 0

**Contrainte :** ne pas utiliser `localStorage` pour persister la conversation entre rechargements de page pour cette première version — une conversation par visite suffit pour le MVP.

---

## Étape 6 — Comportement de l'assistant

Dans le prompt système envoyé à Claude, précise clairement :
- Le rôle : assistant d'EPA_BURKINA, aide les visiteurs à trouver la bonne formation et à s'inscrire
- Le ton : accueillant, professionnel, concis (pas de réponses trop longues dans un widget de chat)
- Les limites : ne jamais inventer de prix ou de dates non présents dans le contexte fourni ; si l'information n'est pas disponible, proposer de contacter EPA directement (numéro/WhatsApp visible dans le footer du site) plutôt que d'improviser
- L'objectif de capture : après avoir répondu utilement, proposer naturellement d'envoyer plus d'informations par WhatsApp/email si l'échange montre un intérêt réel — sans forcer à chaque message

---

## Étape 7 — Test local

- Lance `php artisan serve` et teste manuellement plusieurs scénarios avant tout déploiement :
  - Question sur le prix d'une formation précise
  - Question sur la différence entre deux formations
  - Question sur l'antenne la plus proche
  - Un échange qui se termine par un visiteur donnant son nom et son numéro — vérifier que la capture fonctionne dans `assistant_leads_captures`
  - Une question hors sujet (vérifier que l'assistant reste dans son rôle sans halluciner)
- Ne pas déployer sur le VPS avant validation manuelle complète de ces scénarios

---

## Rappel de portée

Ne touche à aucun autre projet (le logiciel de gestion interne reste hors périmètre de cette tâche — il sera traité séparément). Ne modifie aucune page ou route existante du site en dehors de l'ajout du widget dans le layout.
