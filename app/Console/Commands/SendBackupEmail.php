<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBackupEmail extends Command
{
    protected $signature = 'backup:email {path? : Fichier de sauvegarde à joindre} {--to= : Destinataire} {--error= : Message d\'erreur (envoie une alerte au lieu de la sauvegarde)}';

    protected $description = 'Envoie par email une sauvegarde de la base (ou une alerte si la sauvegarde a échoué)';

    public function handle(): int
    {
        $to = $this->option('to');
        if (! $to) {
            $this->error('Destinataire manquant (--to=).');

            return self::FAILURE;
        }

        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.name');

        if ($error = $this->option('error')) {
            Mail::raw("La sauvegarde automatique de {$host} a ÉCHOUÉ.\n\nDétail : {$error}\n\nConnectez-vous au VPS et consultez /opt/epa-backups/backup.log.", function ($message) use ($to, $host) {
                $message->to($to)->subject("⚠ ÉCHEC sauvegarde {$host} — ".now()->format('d/m/Y'));
            });
            $this->info('Alerte envoyée.');

            return self::SUCCESS;
        }

        $path = $this->argument('path');
        if (! $path || ! is_file($path)) {
            $this->error('Fichier de sauvegarde introuvable.');

            return self::FAILURE;
        }

        $size = number_format(filesize($path) / 1024, 0, ',', ' ');

        Mail::raw("Sauvegarde automatique de la base de {$host}.\nFichier : ".basename($path)." ({$size} Ko).\n\nPour restaurer : gunzip < fichier.sql.gz | docker compose exec -T db sh -c 'mysql -uroot -p\"\$MYSQL_ROOT_PASSWORD\" \"\$MYSQL_DATABASE\"'", function ($message) use ($to, $host, $path) {
            $message->to($to)
                ->subject("Sauvegarde {$host} — ".now()->format('d/m/Y'))
                ->attach($path);
        });

        $this->info("Sauvegarde envoyée à {$to}.");

        return self::SUCCESS;
    }
}
