---
description: Lance le serveur de dev Laravel du site EPA_BURKINA et prend des captures d'écran plein format (Playwright/Chromium headless) des pages publiques pour analyse visuelle ou revue de design. Utiliser quand on demande d'analyser/vérifier le rendu visuel du site, de voir à quoi ressemble une page, ou de confirmer visuellement qu'un changement UI fonctionne.
---

# Capture d'écran du site EPA_BURKINA

Ce projet n'a pas d'outil `chromium-cli` disponible sur cette machine (Windows).
La capture se fait via Playwright directement (déjà installé en devDependency).

## Prérequis (déjà en place, à vérifier si erreur)

```bash
npm ls playwright            # doit lister playwright dans devDependencies
npx playwright install chromium   # si le navigateur n'est pas encore téléchargé
```

## 1. Démarrer le serveur de dev (si pas déjà lancé)

```bash
php artisan serve --port=8123
```

Vérifier qu'il répond avant de continuer :
```bash
curl -sf http://127.0.0.1:8123/ >/dev/null && echo OK
```

Si un serveur tourne déjà sur ce port (fréquent dans une session de travail
en cours), ne pas en relancer un autre — réutiliser celui qui existe.

## 2. Lancer les captures

**Piège connu** : le script utilise `import { chromium } from 'playwright'` (ESM).
Node résout les modules depuis le répertoire du script, donc il FAUT l'exécuter
avec le répertoire courant à la racine du projet (là où `playwright` est dans
`node_modules`) :

```bash
cd "d:\site web epa"
node .claude/skills/screenshot-site/scripts/screenshot.mjs
```

Ça capture par défaut les pages publiques principales (accueil, formations,
actualités, à propos, contact, inscription) en plein format (`fullPage: true`,
viewport 1440×900) dans `.claude/skills/screenshot-site/screenshots/`.

Pour capturer des pages spécifiques (ex: une fiche formation précise) :
```bash
node .claude/skills/screenshot-site/scripts/screenshot.mjs .claude/skills/screenshot-site/screenshots http://127.0.0.1:8123 /formations/data-analytics /actualites/mon-article
```

Le script affiche aussi les erreurs console/page JS rencontrées — à vérifier,
une page peut avoir l'air de charger tout en ayant des erreurs silencieuses.

## 3. Regarder les images

Les captures sont des PNG plein format (peuvent être hautes, la page entière
est capturée). Les lire avec l'outil Read (elles s'affichent comme des images),
pas avec `cat`/`file` qui ne montrent que les métadonnées.

## Piège Git Bash (Windows)

Si tu passes explicitement des chemins commençant par `/` (ex: `/formations/data-analytics`)
en argument sur Git Bash Windows, MSYS peut les convertir en chemin Windows
(`/` devient `C:/Program Files/Git/`). Si ça arrive, relancer avec
`MSYS_NO_PATHCONV=1` devant la commande :

```bash
MSYS_NO_PATHCONV=1 node .claude/skills/screenshot-site/scripts/screenshot.mjs .claude/skills/screenshot-site/screenshots http://127.0.0.1:8123 /formations/data-analytics
```

## Notes

- Le back-office admin (`/admin/...`) nécessite une session authentifiée —
  ce script ne gère pas le login. Pour capturer une page admin, il faudrait
  étendre le script avec une étape de connexion (formulaire `/login`).
- `fullPage: true` capture toute la hauteur de la page, pas seulement le
  viewport visible — utile pour voir une page complète d'un coup, mais les
  images peuvent être très hautes (plusieurs milliers de px).
