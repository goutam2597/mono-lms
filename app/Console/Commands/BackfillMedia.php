<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackfillMedia extends Command
{
    protected $signature = 'media:backfill {--dry-run : List files without creating rows} {--prune : Remove media rows whose file no longer exists}';

    protected $description = 'Scan storage/app/public for existing images and create media rows for any file not yet in the media library';

    /** Folders to scan for user-uploaded media. */
    private array $roots = ['categories', 'courses', 'blog', 'admins', 'instructors', 'students', 'media', 'branding'];

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $adminId = Admin::query()->value('id');

        $created = 0;
        $skipped = 0;

        foreach ($this->roots as $root) {
            $absRoot = storage_path('app/public/'.$root);
            if (! is_dir($absRoot)) {
                continue;
            }

            foreach (File::allFiles($absRoot) as $file) {
                $ext = strtolower($file->getExtension());
                if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'], true)) {
                    continue;
                }

                $relative = str_replace('\\', '/', substr($file->getPathname(), strlen(storage_path('app/public/'))));

                $existing = Media::where('path', $relative)->first();
                if ($existing) {
                    $skipped++;

                    continue;
                }

                $isSvg = $ext === 'svg';
                $width = null;
                $height = null;

                if ($isSvg) {
                    [$width, $height] = MediaService::svgDimensions(file_get_contents($file->getPathname()) ?: '');
                } else {
                    $dims = @getimagesize($file->getPathname());
                    if ($dims !== false) {
                        [$width, $height] = $dims;
                    }
                }

                if ($this->option('dry-run')) {
                    $this->line('[dry-run] Would register: '.$relative);
                    $created++;

                    continue;
                }

                Media::create([
                    'disk' => 'public',
                    'path' => $relative,
                    'name' => $file->getFilename(),
                    'mime' => $isSvg ? 'image/svg+xml' : ($file->getMimeType() ?: 'image/'.$ext),
                    'size' => $file->getSize() ?: 0,
                    'width' => $width,
                    'height' => $height,
                    'admin_id' => $adminId,
                ]);

                $created++;
            }
        }

        if ($this->option('prune')) {
            $pruned = 0;
            foreach (Media::all() as $m) {
                if (! $disk->exists($m->path)) {
                    if ($this->option('dry-run')) {
                        $this->warn('[dry-run] Would prune: '.$m->path);
                    } else {
                        $m->delete();
                    }
                    $pruned++;
                }
            }
            $this->info("Pruned {$pruned} missing media rows.");
        }

        $this->info("Backfill complete. Registered: {$created}, already present: {$skipped}.");

        return self::SUCCESS;
    }
}
