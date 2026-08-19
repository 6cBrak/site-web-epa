#!/bin/bash
set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║       EPA_BURKINA — Mise à jour          ║"
echo "╚══════════════════════════════════════════╝"
echo ""

cd /opt/epa

echo -e "${YELLOW}→ Récupération du code...${NC}"
git pull origin main
echo -e "${GREEN}✓ Code à jour${NC}"

echo -e "${YELLOW}→ Build du container...${NC}"
docker compose build app
echo -e "${GREEN}✓ Build terminé${NC}"

echo -e "${YELLOW}→ Redémarrage...${NC}"
docker compose up -d
echo -e "${GREEN}✓ Container démarré${NC}"

echo -e "${YELLOW}→ Migrations...${NC}"
docker compose exec app php artisan migrate --force
echo -e "${GREEN}✓ Migrations OK${NC}"

echo -e "${YELLOW}→ Mise en cache...${NC}"
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
echo -e "${GREEN}✓ Cache OK${NC}"

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║          Mise à jour terminée !          ║"
echo "╚══════════════════════════════════════════╝"
echo ""
