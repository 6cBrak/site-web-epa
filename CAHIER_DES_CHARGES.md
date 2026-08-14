# Cahier des charges — Site web EPA (Centre de Formation Professionnelle en Informatique & Action Humanitaire)

**Version :** 1.0 (validé par le client — points de la section 8 à compléter au fil du projet)
**Date :** 14/08/2026
**Client :** EPA_BURKINA — "Former les acteurs du développement"

---

## 1. Contexte et présentation de la structure

**EPA_BURKINA** est un centre de formation professionnelle spécialisé dans les métiers de l'**Informatique** et de l'**Action Humanitaire**, implanté au Burkina Faso. Il répond aux besoins croissants en compétences qualifiées des ONG, associations et entreprises qui contribuent au développement du pays.

- **Slogan :** *Former les acteurs du développement*
- **Vision :** devenir un acteur de référence en formation innovante et technique dans les domaines de l'action humanitaire et de l'informatique sur le continent africain.
- **Mission :** former à travers toute l'Afrique des jeunes professionnels compétents, éthiques et responsables, capables d'agir efficacement face aux enjeux majeurs du continent tout en contribuant activement à son développement durable.
- **Deux pôles de formation :**
  - Programme Informatique & Digitalisation
  - Programme Action Humanitaire & Développement
- **Autres activités :** Recherche & développement de projets innovants.
- **Niveaux d'admission :** BEPC – BAC – BAC+
- **Partenaires :** État, ONG, associations locales, entreprises privées, ambassades.

### Antennes

| Antenne | Adresse |
|---|---|
| EPA Ouaga 1 (siège) | 1200 logements, Avenue Babangida, Ouagadougou |
| EPA Bobo | Sarfalao, secteur 17, Bobo-Dioulasso |
| EPA Sahel | Dori, secteur n°2 |

### Contact

- Téléphone / WhatsApp : +226 70 14 32 48 / 07 27 89 07 / 76 83 63 71
- Email : centre-epa.bf@outlook.com
- Réseaux sociaux : Facebook, WhatsApp, Instagram, Twitter/X, LinkedIn

> ⚠️ À confirmer : le numéro `07 66 12 12` apparaît sur un support en plus des trois autres — à vérifier lequel est correct/à jour.

---

## 2. Objectifs du site web

1. Présenter l'organisme, ses valeurs (vision/mission), ses formations et ses 3 antennes de façon professionnelle et crédible auprès des futurs apprenants, partenaires, ONG et bailleurs.
2. Permettre aux visiteurs de découvrir en détail chaque formation (programme, durée, prix, prérequis, sessions à venir) et de **s'inscrire en ligne**.
3. Permettre à l'équipe EPA de **gérer elle-même le contenu du site** (formations, sessions, actualités, inscriptions) sans intervention d'un développeur, via un back-office.
4. Centraliser et faciliter le suivi des candidatures/inscriptions reçues, avec notification automatique par email à l'équipe à chaque nouvelle inscription.
5. Offrir une version bilingue **Français / Anglais** pour toucher les partenaires et organisations internationales.

## 3. Public cible

- Futurs apprenants (niveau BEPC, BAC, BAC+) cherchant une formation professionnalisante.
- ONG, associations et entreprises à la recherche de personnel qualifié ou de partenariats de formation.
- Bailleurs de fonds et ambassades (crédibilité institutionnelle).
- Anciens apprenants / alumni (témoignages, réseau).

---

## 4. Périmètre fonctionnel

### 4.1 Partie publique (front-office)

#### Page d'accueil
- Bannière principale (accroche, slogan, call-to-action "S'inscrire")
- Présentation courte d'EPA (qui sommes-nous en résumé)
- Mise en avant des 2 programmes de formation
- **Chiffres d'impact animés** (compteurs qui s'incrémentent à l'affichage) : nombre d'apprenants formés, taux d'insertion/réussite, nombre de partenaires, nombre de formations — *valeurs à fournir par EPA, éditables depuis le back-office*
- Avantages : formations innovantes, certification prestigieuse (avec renvoi vers la page de vérification de certificat), accompagnement permanent, réseau de partenaires
- Logos des partenaires (État, ONG, associations, entreprises privées, ambassades)
- Dernières actualités (aperçu, 3-4 articles)
- Aperçu des 3 antennes
- Formulaire/bouton de contact rapide

#### Page "Qui sommes-nous"
- Présentation détaillée d'EPA_BURKINA
- Vision / Mission
- Nos atouts
- Présentation des 3 antennes (adresse, carte, contact par antenne)

#### Page "Équipe / Formateurs"
- Présentation nominative des formateurs/consultants principaux (photo, nom, spécialité, courte bio)
- Gérée depuis le back-office (ajout/modification/suppression de membres de l'équipe)

#### Page "Nos formations"
- Liste des formations, filtrable par :
  - Programme (Informatique & Digitalisation / Action Humanitaire & Développement)
  - Antenne (Ouaga / Bobo / Sahel), puisque les sessions proposées peuvent varier par site
- **Fiche formation détaillée** (par formation), gérée depuis le back-office, incluant :
  - Titre, catégorie/programme, image
  - Description / objectifs pédagogiques
  - Programme détaillé (modules)
  - Durée
  - Prix
  - Prérequis / niveau requis
  - Prochaines sessions (dates, antenne(s) concernée(s), **places restantes affichées en temps réel**, **compte à rebours avant le début de la session** pour créer de l'urgence)
  - Bouton "S'inscrire à cette formation"

> 🔧 **Les programmes eux-mêmes sont paramétrables depuis le back-office** : EPA pourra créer, renommer, réorganiser ou désactiver un programme (catégorie de formation) sans intervention d'un développeur — voir "Gestion des programmes" en section 4.2. La liste ci-dessous sert de **données de démarrage (seed)** au lancement, à partir de la documentation fournie ; elle n'est pas figée dans le code.

Liste des formations à intégrer au démarrage (issue de la documentation fournie) :

**Programme Informatique & Digitalisation**
1. Informatique général (Office 365)
2. Intelligence Artificielle (IA)
3. Data Analytics
4. Développement Web / Applications
5. Infographie & Montage vidéo
6. Marketing & Innovations digitales
7. Finance digitale / Fintech
8. Réseaux & Maintenance informatique
9. Énergie renouvelable
10. Électrotechnique
11. Community Management
12. Entrepreneuriat
13. Management des entreprises
14. Secrétariat Comptabilité

**Programme Action Humanitaire & Développement**
1. Gestion de projet humanitaire / développement
2. Suivi-évaluation (MEAL)
3. Gestion RH & Management des ONG/associations
4. Finance-comptabilité des ONG/associations
5. Logistique humanitaire (LH)
6. WASH (Eau-Hygiène-Assainissement)
7. Sécurité alimentaire (SAME)
8. Agro-alimentaire
9. Protection de l'enfance (PESU)
10. Plaidoyer et communication humanitaire
11. Prise en charge psychosociale
12. Paix & gestion pacifique des conflits
13. Genre & inclusion pour le développement
14. Négociation et accès humanitaire

> ⚠️ Deux programmes supplémentaires apparaissent dans le formulaire d'inscription actuel d'EPA mais pas dans le catalogue fourni : **Gestion des Entreprises** et **Métiers Manuels & Techniques (MMT)**, ainsi qu'une formation "Maîtrise de l'IA pour les organisations à but non lucratif". Grâce au paramétrage libre des programmes/formations dans le back-office, ils pourront être ajoutés à tout moment — merci de fournir leur contenu détaillé (formations qu'ils regroupent) quand vous les aurez vérifiés en interne.

> Ces formations sont gérées comme du **contenu dynamique** dans le back-office (ajout/modification/suppression), pas codées en dur, pour permettre d'en ajouter d'autres facilement.

#### Formulaire d'inscription / candidature en ligne

Champs repris et modernisés à partir du formulaire Google Forms actuellement utilisé par EPA (`doc/INSCRIPTIONS EN LIGNE EPA.xlsx`) :

- Nom & Prénom
- Adresse e-mail
- N° de téléphone (WhatsApp)
- Niveau d'étude / dernier diplôme (BEPC, BAC, BAC+…)
- Ville / Pays de résidence
- Nationalité
- **Programme de formation** (catégorie) — voir remarque ci-dessous sur le nombre exact de programmes
- **Formation précise** dans ce programme (liste dépendante du programme choisi)
- Modalité de cours : En ligne / Présentiel-jour *(le formulaire actuel laisse penser qu'il existe aussi une option présentiel-soir — à confirmer)*
- Antenne EPA souhaitée (Ouaga / Bobo / Sahel-Dori)
- Quand souhaitez-vous commencer : immédiatement / prochaine rentrée académique / session spécialisée
- Bénéficiez-vous d'une bourse de réduction via un partenaire ? (Oui/Non) → si Oui, **saisie d'un code promo/parrainage** rattaché au partenaire (remplace la simple liste actuelle, permet de tracer qui a utilisé quel code)
- Comment avez-vous connu EPA ? (réseaux sociaux, ancien étudiant, etc.)
- Commentaire / question sur la formation (facultatif)
- Champ "statut" (étudiant / professionnel) : si **professionnel**, un champ d'**upload de CV** apparaît (facultatif pour les autres profils)

Améliorations apportées par rapport au formulaire Google Forms actuel :
- Champs conditionnels fiabilisés (la formation précise proposée dépend automatiquement du programme sélectionné, sans dupliquer les questions comme c'est le cas actuellement)
- Upload de CV pour les professionnels
- Validation + message de confirmation à l'écran
- **Code promo/parrainage tracé** : chaque code est rattaché à un partenaire dans le back-office (nom, % ou montant de réduction, quota d'utilisations, dates de validité) ; chaque utilisation est enregistrée et consultable par EPA
- **Email automatique** :
  - au candidat (accusé de réception)
  - à l'équipe EPA / l'antenne concernée (notification de nouvelle candidature)
- **Accusé de réception en PDF** généré automatiquement et joint à l'email du candidat (récapitulatif de sa candidature)
- **Suivi de candidature** : un lien unique (envoyé par email) permet au candidat de consulter à tout moment le statut de sa candidature (reçue / en cours d'examen / confirmée / refusée) sans avoir à créer de compte
- Enregistrement de la candidature dans le back-office (liste, filtrage par formation/antenne/statut, export), au lieu d'un simple export Excel manuel

> ⚠️ **Point important à clarifier** : le formulaire actuel fait apparaître **4 programmes** (Informatique & Digitalisation, Action Humanitaire & Développement, **Gestion des Entreprises**, **Métiers Manuels & Techniques - MMT**), alors que la documentation/catalogue fournie n'en présente que 2 (Informatique et Action Humanitaire — les formations "Entrepreneuriat" et "Management des entreprises" y sont rattachées au programme Informatique). De même, une formation "Maîtrise de l'IA pour les organisations à but non lucratif" apparaît dans les réponses mais pas dans le catalogue. → **Merci de confirmer la liste définitive et à jour des programmes et formations** avant le développement du back-office (voir section 8).

#### Page "Actualités"
- Liste d'articles (annonces, événements, success stories d'apprenants)
- Page détail par article (titre, image, contenu, date)
- Gérée depuis le back-office (créer/modifier/publier/dépublier)

#### Page "Nos partenaires"
- Logos et/ou liste des catégories de partenaires (État, ONG, associations locales, entreprises privées, ambassades)

#### Page "Contact"
- Formulaire de contact général (non lié à une formation)
- Coordonnées des 3 antennes (adresse, téléphone, email, carte)
- Liens réseaux sociaux

#### Page "Vérifier un certificat"
- Champ de saisie d'un numéro de certificat (ou scan direct d'un QR code imprimé sur le certificat physique)
- Affichage du résultat : nom de l'apprenant, formation suivie, antenne, date d'obtention, statut (valide/révoqué) si le numéro correspond à un certificat existant, sinon message "certificat introuvable"
- Objectif : rassurer les employeurs/ONG/partenaires sur l'authenticité des certifications délivrées par EPA

#### Bilinguisme (FR / EN)
- Sélecteur de langue (FR par défaut / EN)
- Tout le contenu géré dans le back-office doit être saisissable dans les deux langues (formations, actualités, pages statiques)

#### Éléments transverses (présents sur tout le site)
- **Bouton WhatsApp flottant** (visible sur toutes les pages) pointant vers le numéro WhatsApp principal d'EPA, pour un contact direct en un clic

### 4.2 Back-office (espace d'administration)

Accès sécurisé (authentification) pour l'équipe EPA, avec gestion complète du contenu :

- **Gestion des programmes** : créer / renommer / réorganiser / activer-désactiver les programmes (catégories de formation), nom + description FR/EN, icône/couleur — permet d'ajouter un programme (ex: Gestion des Entreprises, MMT) sans développement supplémentaire
- **Gestion des formations** : créer / modifier / supprimer / publier, rattachées à un programme et à une/plusieurs antennes, versions FR/EN
- **Gestion des sessions** : dates, antenne, places disponibles, statut (ouverte/complète/clôturée)
- **Gestion des inscriptions/candidatures** : liste, recherche, filtres (formation, antenne, statut, date), changement de statut (nouvelle / contactée / confirmée / refusée), export (CSV/Excel)
- **Gestion des actualités** : créer / modifier / publier / dépublier articles, FR/EN
- **Gestion des antennes** : coordonnées, description
- **Gestion des partenaires** : logos, noms, catégories
- **Gestion de l'équipe/formateurs** : photo, nom, spécialité, bio
- **Gestion des certificats** : émission d'un certificat pour un apprenant (nom, formation, antenne, date), génération automatique d'un numéro unique + QR code, possibilité de révoquer un certificat ; alimente la page publique "Vérifier un certificat"
- **Gestion des codes promo/parrainage** : création de codes rattachés à un partenaire, % ou montant de réduction, quota et dates de validité, historique des utilisations
- **Gestion des chiffres clés** : édition des statistiques affichées sur l'accueil (apprenants formés, taux d'insertion, nb de partenaires, etc.)
- **Gestion des utilisateurs admin** : rôle unique au lancement (tous les admins ont accès à l'ensemble des antennes) — évolution possible vers des rôles différenciés par antenne dans une phase ultérieure
- **Tableau de bord** : statistiques simples (nb inscriptions par formation/mois, formations les plus demandées, codes promo les plus utilisés)

---

## 5. Exigences techniques

| Aspect | Choix retenu |
|---|---|
| Back-end | PHP / **Laravel** |
| Base de données | MySQL |
| Front-end | Blade (+ éventuellement un peu de JS/Alpine.js pour l'interactivité) — *à confirmer* |
| Hébergement cible | VPS |
| Environnement de dev local | XAMPP (Apache/MySQL/PHP) |
| Responsive | Site 100% adaptatif (mobile, tablette, desktop) |
| Langues | FR (par défaut) + EN |
| Emails | Envoi via SMTP (ex: service mail du VPS ou un fournisseur tiers type Brevo/Mailgun — *à définir*) |
| Sécurité | Authentification back-office sécurisée, validation des formulaires, protection CSRF/XSS/injections SQL (standards Laravel), sauvegardes régulières de la base de données |
| SEO | Balises méta, URLs propres, sitemap.xml, structure de titres sémantique |

---

## 6. Charte graphique (issue des supports fournis + `logo.jpeg`)

- **Logo (reçu, `logo.jpeg`) :** "EPA" en typographie contour noir, chapeau de diplômé rouge, main tendue rouge sous le texte, associé au pictogramme "e+" multicolore (segments vert, violet, magenta, orange, bleu, "e+" gris au centre) + baseline "Formations professionnelles" / "Former les acteurs du développement"
- **Couleurs dominantes :** rouge/orange (logo, dégradés bandeaux), noir (texte), avec touches vert/violet/magenta/orange/bleu issues du pictogramme "e+"
- **Style visuel :** institutionnel, dynamique, orienté "impact humanitaire + tech", photos de terrain (Afrique, apprenants, action humanitaire) et de salles de formation
- Charte graphique définitive (couleurs exactes en HEX, typographies) toujours à confirmer (voir section 8, point 2) — en l'absence de précision, une palette sera proposée par déduction du logo et des supports lors de la maquette

---

## 7. Arborescence proposée du site

```
Accueil
├── Qui sommes-nous
│   ├── Vision / Mission
│   ├── Nos atouts
│   └── Nos antennes (Ouaga / Bobo / Sahel)
├── Nos formations
│   ├── Programme Informatique & Digitalisation
│   │   └── [Fiche par formation]
│   └── Programme Action Humanitaire & Développement
│       └── [Fiche par formation]
├── S'inscrire (formulaire de candidature)
│   └── Suivi de candidature (lien unique par email)
├── Actualités
│   └── [Article détail]
├── Équipe / Formateurs
├── Vérifier un certificat
├── Nos partenaires
├── Contact
├── Bouton WhatsApp flottant (toutes pages)
└── (FR/EN) — sélecteur de langue sur toutes les pages

Back-office (/admin)
├── Tableau de bord
├── Programmes
├── Formations & sessions
├── Inscriptions/candidatures
├── Certificats
├── Codes promo / parrainage
├── Chiffres clés (accueil)
├── Actualités
├── Antennes
├── Équipe / Formateurs
├── Partenaires
└── Utilisateurs admin
```

---

## 8. Points encore ouverts

1. **Contenu définitif des programmes additionnels** : les programmes sont paramétrables dans le back-office (résolu architecturalement — voir 4.2 "Gestion des programmes"). Reste à fournir : la liste des formations regroupées sous "Gestion des Entreprises" et "Métiers Manuels & Techniques (MMT)", et confirmer si "Maîtrise de l'IA pour les ONG" est une formation à part entière, pour les intégrer aux données de démarrage.
2. **Charte graphique définitive** : logo reçu (`logo.jpeg`) ✅. Avez-vous des couleurs officielles exactes (codes HEX) et une police de caractères imposée, ou peut-on les déduire du logo et des supports fournis ?
3. **Modalités de cours** : le formulaire actuel propose "En ligne" et "Présentiel-jour" — existe-t-il aussi un "Présentiel-soir" ou d'autres modalités ?
4. **Bourses/réductions partenaires** : merci de fournir la liste à jour des associations/entreprises partenaires, ainsi que le pourcentage/montant de réduction associé à chacune, pour créer leurs codes promo.
5. **Chiffres d'impact réels** : merci de fournir les chiffres à afficher en compteurs animés sur l'accueil (nb d'apprenants formés, taux d'insertion/réussite, nb de partenaires, etc.) et leur fréquence de mise à jour souhaitée.
6. **Format du certificat** : avez-vous déjà un modèle de certificat (PDF/design) sur lequel ajouter le numéro/QR code de vérification, ou faut-il en concevoir un ?
7. **Paiement en ligne** : confirmé absent du périmètre initial — à ajouter dans une phase 2 si besoin (mobile money évoqué comme piste) ?
8. **Nom de domaine** : déjà réservé selon le client, mais le nom exact reste à confirmer avant le déploiement.
9. **Contenu à fournir** : logos partenaires (fichiers), photos officielles supplémentaires, textes définitifs des fiches de formation (durée/prix/prérequis précis par formation) — actuellement seuls les titres des formations sont connus. Photos/bios de l'équipe pour la page Formateurs.

---

## 9. Prochaines étapes

1. Validation de ce cahier des charges par EPA (ajustements, réponses aux points de la section 8).
2. Validation de la charte graphique / maquette (wireframes des pages clés).
3. Mise en place de l'environnement de développement (Laravel, structure du projet).
4. Développement du back-office puis du site public.
5. Intégration des contenus réels (formations, textes, images, partenaires).
6. Tests (fonctionnels, responsive, emails, bilingue).
7. Déploiement sur le VPS.
