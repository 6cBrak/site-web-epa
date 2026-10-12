#!/bin/bash
# Sauvegarde automatique EPA_BURKINA : base MySQL (compressée, envoyée par email)
# + fichiers téléversés (chaque dimanche, gardés sur le VPS uniquement).
#
# Cron conseillé (tous les jours à 02:00) :
#   0 2 * * * /opt/epa/backup.sh >> /opt/epa-backups/backup.log 2>&1
#
# Destinataire : variable BACKUP_EMAIL dans /opt/epa/.env

set -uo pipefail

APP_DIR=/opt/epa
BACKUP_DIR=/opt/epa-backups
KEEP_DAYS=14

cd "$APP_DIR" || exit 1
mkdir -p "$BACKUP_DIR"
chmod 700 "$BACKUP_DIR"

TO=$(grep -E '^BACKUP_EMAIL=' .env | head -n1 | cut -d= -f2- | tr -d '"' | tr -d "'" | tr -d '\r')
STAMP=$(date +%F_%H%M)
FILE="$BACKUP_DIR/epa_db_$STAMP.sql.gz"

log() { echo "[$(date '+%F %T')] $*"; }

fail() {
    log "ÉCHEC : $1"
    rm -f "$FILE"
    if [ -n "$TO" ]; then
        docker compose exec -T app php artisan backup:email --to="$TO" --error="$1" || true
    fi
    exit 1
}

log "Sauvegarde de la base..."
docker compose exec -T db sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --single-transaction --routines "$MYSQL_DATABASE" 2>/dev/null' | gzip > "$FILE"
[ "${PIPESTATUS[0]}" -eq 0 ] || fail "mysqldump a échoué"

# Une base vide compressée fait quelques dizaines d'octets : en dessous de 1 Ko, c'est anormal.
[ "$(stat -c%s "$FILE")" -gt 1024 ] || fail "fichier de sauvegarde anormalement petit"
gzip -t "$FILE" || fail "archive corrompue"
chmod 600 "$FILE"
log "OK : $FILE ($(du -h "$FILE" | cut -f1))"

if [ -n "$TO" ]; then
    docker compose cp "$FILE" app:/tmp/backup.sql.gz >/dev/null 2>&1 || fail "copie vers le container impossible"
    if docker compose exec -T app php artisan backup:email /tmp/backup.sql.gz --to="$TO"; then
        log "Email envoyé à $TO"
    else
        log "ATTENTION : sauvegarde faite mais email non envoyé"
    fi
    docker compose exec -T app rm -f /tmp/backup.sql.gz
else
    log "BACKUP_EMAIL absent du .env : pas d'email envoyé"
fi

# Fichiers téléversés (images, CV, logos) : chaque dimanche, gardés 4 semaines sur le VPS
if [ "$(date +%u)" -eq 7 ]; then
    STORAGE_FILE="$BACKUP_DIR/epa_storage_$STAMP.tar.gz"
    if docker compose exec -T app tar czf - -C /app/storage/app public > "$STORAGE_FILE"; then
        chmod 600 "$STORAGE_FILE"
        log "OK : $STORAGE_FILE ($(du -h "$STORAGE_FILE" | cut -f1))"
    else
        rm -f "$STORAGE_FILE"
        log "ATTENTION : sauvegarde des fichiers téléversés échouée"
    fi
    find "$BACKUP_DIR" -name 'epa_storage_*.tar.gz' -mtime +28 -delete
fi

# Rotation des sauvegardes de base
find "$BACKUP_DIR" -name 'epa_db_*.sql.gz' -mtime +"$KEEP_DAYS" -delete
log "Terminé."
