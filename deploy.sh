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

echo -e "${YELLOW}→ Attente du démarrage (migrations + cache lancés par l'entrypoint)...${NC}"
ready=0
for i in $(seq 1 60); do
    if docker compose exec -T app supervisorctl status nginx 2>/dev/null | grep -q RUNNING; then
        ready=1
        break
    fi
    sleep 2
done
if [ "$ready" -ne 1 ]; then
    echo "✗ Le container n'a pas démarré. Voir : docker compose logs --tail=60 app"
    exit 1
fi
echo -e "${GREEN}✓ Application démarrée, migrations et cache appliqués${NC}"

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║          Mise à jour terminée !          ║"
echo "╚══════════════════════════════════════════╝"
echo ""
