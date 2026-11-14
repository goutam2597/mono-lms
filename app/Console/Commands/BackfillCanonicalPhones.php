<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Student;
use App\Services\PhoneNumberService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Audit or safely backfill canonical contact phone values for the three
 * account tables. Values the parser cannot resolve (bare legacy numbers with
 * no resolvable dial code) are reported as skips, never guessed — rerun after
 * enabling the missing country in Settings → Locations.
 */
class BackfillCanonicalPhones extends Command
{
    protected $signature = 'phones:backfill {--apply : Persist safe canonical values after auditing} {--report= : A private storage-relative JSON report path}';

    protected $description = 'Audit or safely backfill canonical phone_e164 / phone_country values';

    public function handle(PhoneNumberService $phones): int
    {
        $apply = (bool) $this->option('apply');
        $report = (string) ($this->option('report') ?: 'reports/phone-backfill-'.now()->format('Ymd-His').'.json');
        $summary = ['mode' => $apply ? 'apply' : 'dry-run', 'updated' => 0, 'skipped' => 0, 'already_canonical' => 0, 'models' => [], 'skips' => []];

        foreach ($this->targets() as [$class, $rawColumn, $canonicalColumn, $countryColumn]) {
            $key = class_basename($class).'.'.$rawColumn;
            $summary['models'][$key] = ['updated' => 0, 'skipped' => 0, 'already_canonical' => 0];

            $class::query()
                ->whereNotNull($rawColumn)
                ->orderBy('id')
                ->chunkById(200, function ($records) use ($phones, $apply, &$summary, $class, $rawColumn, $canonicalColumn, $countryColumn, $key): void {
                    foreach ($records as $record) {
                        /** @var Model $record */
                        $phone = $phones->parseStored((string) $record->getAttribute($rawColumn));
                        if (! $phone->isValid()) {
                            $summary['skipped']++;
                            $summary['models'][$key]['skipped']++;
                            $summary['skips'][] = ['model' => $class, 'id' => $record->getKey(), 'field' => $rawColumn, 'reason' => $phone->error ?: 'ambiguous_legacy_value'];

                            continue;
                        }

                        if ($record->getAttribute($canonicalColumn) === $phone->e164 && $record->getAttribute($countryColumn) === $phone->country?->code) {
                            $summary['already_canonical']++;
                            $summary['models'][$key]['already_canonical']++;

                            continue;
                        }

                        if ($apply) {
                            $record->forceFill([
                                $canonicalColumn => $phone->e164,
                                $countryColumn => $phone->country?->code,
                            ])->saveQuietly();
                        }

                        $summary['updated']++;
                        $summary['models'][$key]['updated']++;
                    }
                });
        }

        // The report identifies only record IDs and reasons, never contact values.
        Storage::disk('local')->put($report, json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info(sprintf(
            '%s: %d eligible, %d already canonical, %d skipped. Private report: storage/app/%s',
            $apply ? 'Backfill complete' : 'Dry run complete',
            $summary['updated'],
            $summary['already_canonical'],
            $summary['skipped'],
            $report,
        ));

        return self::SUCCESS;
    }

    /** @return array<int, array{class-string<Model>, string, string, string}> */
    private function targets(): array
    {
        return [
            [Student::class, 'phone', 'phone_e164', 'phone_country'],
            [Instructor::class, 'phone', 'phone_e164', 'phone_country'],
            [Admin::class, 'phone', 'phone_e164', 'phone_country'],
        ];
    }
}
