#!/bin/bash
set -e

# ═══════════════════════════════════════════════
#  EPA_BURKINA — Script d'installation automatique
# ═══════════════════════════════════════════════

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║      EPA_BURKINA — Installation          ║"
echo "╚══════════════════════════════════════════╝"
echo ""

# ── Vérifications ──────────────────────────────
if ! command -v docker &> /dev/null; then
    echo -e "${RED}✗ Docker n'est pas installé.${NC}"
    exit 1
fi

if ! docker compose version &> /dev/null; then
    echo -e "${RED}✗ Docker Compose n'est pas installé.${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Docker détecté${NC}"

# ── Collecte des informations ───────────────────
echo ""
echo "Réponds aux questions suivantes :"
echo ""

read -p "  Domaine (ex: epa-burkina.org) : " DOMAIN
read -p "  Mot de passe MySQL root (choisis-en un fort) : " DB_PASSWORD
read -p "  Mot de passe admin back-office (remplace changeme123) : " ADMIN_PASSWORD
read -p "  Hôte SMTP (ex: smtp.hostinger.com) : " MAIL_HOST
read -p "  Port SMTP (ex: 465) : " MAIL_PORT
read -p "  Utilisateur SMTP (ex: contact@epa-burkina.org) : " MAIL_USERNAME
read -s -p "  Mot de passe SMTP : " MAIL_PASSWORD
echo ""
read -p "  Chiffrement SMTP (ssl/tls) [ssl] : " MAIL_ENCRYPTION
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-ssl}

# ── Génération APP_KEY ──────────────────────────
echo ""
echo -e "${YELLOW}→ Génération de la clé Laravel...${NC}"
APP_KEY=$(docker run --rm php:8.2-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;")
echo -e "${GREEN}✓ APP_KEY générée${NC}"

# ── Création du .env ────────────────────────────
echo -e "${YELLOW}→ Création du fichier .env...${NC}"

cat > .env <<EOF
APP_NAME="EPA_BURKINA"
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=https://${DOMAIN}
APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr

DOMAIN=${DOMAIN}

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_NAME=epa_db
DB_DATABASE=epa_db
DB_USERNAME=root
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_HOST=${MAIL_HOST}
MAIL_PORT=${MAIL_PORT}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION}
MAIL_FROM_ADDRESS=${MAIL_USERNAME}
MAIL_FROM_NAME="EPA_BURKINA"

LOG_CHANNEL=stack
LOG_LEVEL=error
EOF

echo -e "${GREEN}✓ .env créé${NC}"

# ── Build et lancement Docker ───────────────────
echo ""
echo -e "${YELLOW}→ Build et démarrage des containers (peut prendre 2-3 minutes)...${NC}"
echo ""
docker compose up -d --build

# ── Attente que la base de données soit prête ───
echo ""
echo -e "${YELLOW}→ Attente que la base de données soit prête...${NC}"
sleep 15

# ── Migrations et seeds ─────────────────────────
echo -e "${YELLOW}→ Migrations...${NC}"
docker compose exec app php artisan migrate --force
echo -e "${GREEN}✓ Migrations OK${NC}"

echo -e "${YELLOW}→ Seeder des données initiales (antennes, programmes, réglages)...${NC}"
docker compose exec app php artisan db:seed --force
echo -e "${GREEN}✓ Données insérées${NC}"

# ── Sécurisation du compte admin ────────────────
echo -e "${YELLOW}→ Application du mot de passe admin choisi...${NC}"
docker compose exec app php artisan tinker --execute="\App\Models\User::where('email','admin@epa.local')->update(['password'=>bcrypt('${ADMIN_PASSWORD}')]);"
echo -e "${GREEN}✓ Mot de passe admin mis à jour${NC}"

# ── Résumé ──────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════════╗"
echo "║          Installation terminée !         ║"
echo "╚══════════════════════════════════════════╝"
echo ""
echo -e "  Site       : ${GREEN}https://${DOMAIN}${NC}"
echo -e "  Admin      : ${GREEN}https://${DOMAIN}/admin${NC}"
echo -e "  Login admin: admin@epa.local (mot de passe : celui saisi ci-dessus)"
echo ""
echo -e "${YELLOW}⚠  N'oublie pas :${NC}"
echo "  1. Pointer le DNS (A record) vers l'IP de ce VPS"
echo "  2. Vérifier l'envoi d'emails (formulaire de contact + candidatures)"
echo "  3. Compléter/publier les formations et actualités depuis le back-office"
echo ""
